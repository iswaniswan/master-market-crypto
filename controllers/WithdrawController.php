<?php

namespace app\controllers;

use app\components\Helper;
use Yii;
use app\components\Mode;
use app\components\Session;
use app\models\FundActive;
use app\models\FundPassive;
use app\models\FundRef;
use app\models\Withdraw;
use app\models\WithdrawSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* custom controller, theme uplon integrated */
/**
 * WithdrawController implements the CRUD actions for Withdraw model.
 */
class WithdrawController extends Controller
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
     * Lists all Withdraw models.
     *
     * @return string
     */
    public function actionIndexAdmin()
    {
        $searchModel = new WithdrawSearch();
        $searchModel->idGroups = Session::getIdGroupAsAdminGroup();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        return $this->render('index-admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexDistributor()
    {
        $searchModel = new WithdrawSearch();
        $searchModel->idGroups = Session::getIdGroupAsAdminGroup();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        return $this->render('index-admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHistoryMember()
    {
        $searchModel = new WithdrawSearch();
        $searchModel->id_member = Session::getIdMember();

        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('history-member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Withdraw model.
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
     * Creates a new Withdraw model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Withdraw();

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

    public function actionCreateActive()
    {
        $model = new Withdraw();
        $model->tipe = Withdraw::ACTIVE;
        
        if (Session::getIdMember() == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        $model->id_member = Session::getIdMember();
        
        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            $model->id_transaksi = Helper::generateNomorTransaksi();
            $model->amount = str_replace(".", "", $model->amount);
            $model->fee = str_replace(".", "", $model->fee);
            $model->nett = str_replace(".", "", $model->nett);

            /** cek saldo */
            $available = FundActive::getBalance(Session::getIdMember());
            if ($available < $model->amount) {
                Yii::$app->session->setFlash('error', 'Saldo tidak mencukupi');
                return $this->redirect(['withdraw/create-active']);
            }

            if ($model->save()) {

                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect(['withdraw/history-member']);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('create-active', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::CREATE
        ]);
    }

    public function actionCreatePassive()
    {
        $model = new Withdraw();
        $model->tipe = Withdraw::PASSIVE;
        
        if (Session::getIdMember() == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        $model->id_member = Session::getIdMember();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            $model->amount = str_replace(".", "", $model->amount);
            $model->fee = str_replace(".", "", $model->fee);
            $model->nett = str_replace(".", "", $model->nett);

            /** cek saldo */
            $available = FundPassive::getBalance(Session::getIdMember());
            if ($available < $model->amount) {
                Yii::$app->session->setFlash('error', 'Saldo tidak mencukupi');
                return $this->redirect(['withdraw/create-active']);
            }


            if ($model->save()) {

                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('create-passive', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::CREATE
        ]);
    }

    /**
     * Updates an existing Withdraw model.
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
     * Deletes an existing Withdraw model.
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
     * Finds the Withdraw model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Withdraw the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Withdraw::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // public function actionHistoryWithdraw($id)
    // {
    //     $searchModel = new WithdrawSearch();
    //     $searchModel->id = $id;
    //     $dataProvider = $searchModel->search($this->request->queryParams);
    //     $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

    //     return $this->render('history-member', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    public function actionApprove($id) 
    {
        $model = $this->findModel($id);

        $model->updateAttributes([
            'status' => 1
        ]);

        if ($model->tipe == Withdraw::ACTIVE) {

            $fund = new FundActive([
                'id_member' => $model->id_member,
                'debet' => $model->amount,
                'id_trx' => $model->id_transaksi,
                'id_fund_ref' => FundRef::WITHDRAW
            ]);
        } else {

            $fund = new FundPassive([
                'id_member' => $model->id_member,
                'debet' => $model->amount,
                'id_trx' => $model->id_transaksi,
                'id_fund_ref' => FundRef::WITHDRAW
            ]);
        }

        $fund->save();

        Yii::$app->session->setFlash('success', 'Success');

        if (Session::isAdmin()) {
            return $this->redirect(['withdraw/index-admin']);
        }
        return $this->redirect(['withdraw/index-distributor']);
    }
    
}
