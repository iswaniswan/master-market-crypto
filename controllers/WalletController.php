<?php

namespace app\controllers;

use app\components\Session;
use app\models\FundRef;
use app\models\FundTicket;
use app\models\Member;
use Yii;
use app\components\Mode;
use app\models\Wallet;
use app\models\WalletSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* custom controller, theme uplon integrated */
/**
 * WalletController implements the CRUD actions for Wallet model.
 */
class WalletController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Wallet models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Session::isAdmin() == false) {
            return $this->actionIndexMember();
        }

        $searchModel = new WalletSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexMember()
    {
        $searchModel = new WalletSearch();
        $searchModel->id_member = Session::getIdMember();
        $searchModel->grouping = true;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Wallet model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $referrer = $this->request->referrer;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'referrer' => $referrer,
            'mode' => Mode::READ
        ]);
    }

    /**
     * Creates a new Wallet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Wallet();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('view', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::CREATE
        ]);
    }

    /**
     * Updates an existing Wallet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Update success.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'An error occured when update.');
        }

        return $this->render('view', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::UPDATE
        ]);
    }

    /**
     * Deletes an existing Wallet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Delete success');
        } else {
            Yii::$app->session->setFlash('error', 'An error occured when delete.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Wallet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Wallet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Wallet::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionFund()
    {
        if (!Session::isAdmin()) {
            return $this->redirect(['dashboard/index']);
        }

        $member = Member::findOne(['id' => Session::getIdMember()]);

        /** debet */
        $model = new FundTicket();
        $model->id_member = Session::getIdMember();
        $model->id_fund_ref = FundRef::RIPS;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            /** credit */
            $fundTicket = new FundTicket([
                'id_member' => $model->id_member_ref,
                'credit' => $model->debet,
                'id_fund_ref' => FundRef::TOPUP,
                'id_member_ref' => $model->id_member
            ]);

            if ($model->save() and $fundTicket->save()) {
                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect(['/wallet/index-history']);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('view', [
            'model' => $model,
            'member' => $member,
            'referrer' => $referrer,
            'mode' => Mode::CREATE,
        ]);
    }

    public function actionIndexHistory($id_crypto)
    {
        $searchModel = new WalletSearch();
        $searchModel->id_member = Session::getIdMember();
        $searchModel->id_crypto = $id_crypto;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}
