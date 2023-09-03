<?php

namespace app\controllers;

use Yii;
use app\components\Mode;
use app\components\Session;
use app\models\FundRef;
use app\models\FundTicket;
use app\models\FundTicketSearch;
use app\models\Member;
use app\models\Role;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* custom controller, theme uplon integrated */
/**
 * FundTicketController implements the CRUD actions for FundTicket model.
 */
class FundTicketController extends Controller
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
     * Lists all FundTicket models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Session::isAdmin()) {
            return $this->redirect(['/dashboard/index']);
        }

        $member = Member::findOne(['id' => Session::getIdMember()]);

        $ticket = new FundTicket();

        $searchModel = new FundTicketSearch();
        $searchModel->id_member = $member->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-admin', [            
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member,
            'ticket' => $ticket
        ]);
    }

    public function actionIndexDistributor()
    {
        if (!Session::isDistributor()) {
            return $this->redirect(['/dashboard/index']);
        }

        $member = Member::findOne(['id' => Session::getIdMember()]);

        $searchModel = new FundTicketSearch();
        $searchModel->id_member = $member->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-distributor', [            
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member
        ]);
    }

    public function actionIndexMember()
    {
        $member = Member::findOne(['id' => Session::getIdMember()]);

        $searchModel = new FundTicketSearch();
        $searchModel->id_member = $member->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-member', [            
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member
        ]);
    }

    /**
     * Displays a single FundTicket model.
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
     * Creates a new FundTicket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new FundTicket();

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

    public function actionCreateDistributor($id_member)
    {
        $distributor = Member::findOne(['id' => $id_member]);

        $granted = false;
        if ($distributor != null and Session::isAdmin()) {
            $granted = true;
        }

        if (!$granted) {
            return $this->redirect(['dashboard/index']);
        }

        $model = new FundTicket();
        $model->id_member = $id_member;
        $model->id_fund_ref = FundRef::TOPUP;
        $model->id_member_ref = Session::getIdMember();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect(['/member/index-admin-distributor']);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('create-distributor', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::CREATE,
            'distributor' => $distributor
        ]);
    }

    public function actionSendDistributor()
    {
        if (!Session::isDistributor()) {
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

            $isValid = false;
            $availableTicket = FundTicket::getBalance($model->id_member);
            if (($availableTicket - $model->debet) >= 0) {
                $isValid = true;
                /** credit */
                $fundTicket = new FundTicket([
                    'id_member' => $model->id_member_ref,
                    'credit' => $model->debet,
                    'id_fund_ref' => FundRef::TOPUP,
                    'id_member_ref' => $model->id_member
                ]);
            }

            if ($isValid and $model->save() and $fundTicket->save()) {
                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect(['/fund-ticket/index-distributor']);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('send-distributor', [
            'model' => $model,
            'member' => $member,
            'referrer' => $referrer,
            'mode' => Mode::CREATE,
        ]);
    }

    public function actionSendAdmin()
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
                return $this->redirect(['/fund-ticket/index']);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('send-admin', [
            'model' => $model,
            'member' => $member,
            'referrer' => $referrer,
            'mode' => Mode::CREATE,
        ]);
    }

    public function actionSendMember()
    {
        $member = Member::findOne(['id' => Session::getIdMember()]);

        /** debet */
        $model = new FundTicket();
        $model->id_member = Session::getIdMember();
        $model->id_fund_ref = FundRef::RIPS;


        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            $isValid = false;
            $availableTicket = FundTicket::getBalance($model->id_member);
            if (($availableTicket - $model->debet) >= 0) {
                $isValid = true;
                /** credit */
                $fundTicket = new FundTicket([
                    'id_member' => $model->id_member_ref,
                    'credit' => $model->debet,
                    'id_fund_ref' => FundRef::TOPUP,
                    'id_member_ref' => $model->id_member
                ]);
            }

            if ($isValid and $model->save() and $fundTicket->save()) {
                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect(['/fund-ticket/index-member']);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('send-member', [
            'model' => $model,
            'member' => $member,
            'referrer' => $referrer,
            'mode' => Mode::CREATE,
        ]);
    }

    /**
     * Updates an existing FundTicket model.
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
     * Deletes an existing FundTicket model.
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
     * Finds the FundTicket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return FundTicket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FundTicket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
