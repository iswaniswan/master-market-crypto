<?php

namespace app\controllers;

use Yii;
use app\components\Mode;
use app\components\Session;
use app\models\Deposit;
use app\models\DepositSearch;
use app\models\Downline;
use app\models\FundActive;
use app\models\FundPassive;
use app\models\FundRef;
use app\models\FundTicket;
use app\models\Groups;
use app\models\Member;
use app\models\Paket;
use app\models\Role;
use Exception;
use PhpParser\Node\Expr\Throw_;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;

use function PHPUnit\Framework\throwException;

/* custom controller, theme uplon integrated */
/**
 * DepositController implements the CRUD actions for Deposit model.
 */
class DepositController extends Controller
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
     * Lists all Deposit models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DepositSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexAdmin()
    {
        $searchModel = new DepositSearch();
        $searchModel->idGroups = Session::getIdGroupAsAdminGroup();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexDistributor()
    {
        $searchModel = new DepositSearch();
        $searchModel->idGroups = Session::getIdGroupAsAdminGroup();
        
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Deposit model.
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

    public function actionHistoryDeposit($id)
    {
        $searchModel = new DepositSearch();
        $searchModel->id = $id;
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        return $this->render('history-member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Deposit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Deposit();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            if ($model->save()) {

                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->actionHistoryMember();
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('view', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::CREATE
        ]);
    }

    public function actionCreateMember($id_paket=null)
    {
        $model = new Deposit();
        $model->id_member = Session::getIdMember();

        if ($id_paket != null) {
            $model->id_paket = $id_paket;
            $paket = Paket::findOne(['id' => $id_paket]);
            $model->harga_paket = $paket->price;
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];            

            $total_bayar = str_replace(".", "", $model->total_bayar);
            $model->total_bayar = $total_bayar;

            $model->id_transaksi = $model->generateNomorTransaksi();

            if ($model->save()) {               

                $member = $model->member;

                $member->updateAttributes([
                    'id_paket' => $model->id_paket
                ]);

                /** update ticket */
                $fundTicket = new FundTicket([
                    'id_member' => $member->id,
                    'debet' => 1,
                    'id_fund_ref' => FundRef::RIPS,
                    'id_member_ref' => FundTicket::DEPOSIT
                ]);
                $fundTicket->save();

                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect(['deposit/history-member']);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('create-member', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::CREATE
        ]);
    }

    public function actionHistoryMember()
    {
        $searchModel = new DepositSearch();
        $searchModel->id_member = Session::getIdMember();

        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        return $this->render('history-member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Deposit model.
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
     * Deletes an existing Deposit model.
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
     * Finds the Deposit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Deposit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deposit::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionApprove($id) 
    {
        $model = $this->findModel($id);

        // var_dump($model); die();

        /** aktivasi member */
        $member = $model->member;

        /** create referral code */        
        $randomString = strtoupper(
            Yii::$app->security->generateRandomString(6)
        );
        while (Member::isCodeReferralExists($randomString)) {
            $randomString = strtoupper(
                Yii::$app->security->generateRandomString(6)
            );
        }

        $member->updateAttributes([
            'id_paket' => $model->id_paket,
            'is_active' => Member::ACTIVE,
            'id_member_approved_by' => Session::getIdMember(),
            'date_active' => date('Y-m-d H:i:s'),
            'referral_code' => $randomString
        ]);

        /** update role user */
        if ($member->isActive()) {
            $user = $member->user;
            $roleByPaket = $member->getRoleByPaket();
            $user->updateAttributes([
                'id_role' => $roleByPaket
            ]);
        }

        // /** findOrCreate Group */
        // if (@$member->user->role->id == Role::DISTRIBUTOR) {
        //     $group = new Groups([
        //         'id_group' => $member->id.$member->user->id,
        //         'id_member' => $member->id,
        //         'is_admin_group' => Groups::ADMIN
        //     ]);

        //     $group->save();

        // } else {
        //     /** find group by referral */
        //     $memberSponsor = Member::findOne([
        //         'id' => $member->id_member_sponsor
        //     ]);

        //     $group = Groups::findOne([
        //         'id_member' => $memberSponsor->id
        //     ]);

        //     /** insert new member into group */
        //     $memberGroup = new Groups([
        //         'id_group' => $group->id_group,
        //         'id_member' => $member->id
        //     ]);
        //     $memberGroup->save();
        // }

        /** apply bonus */
        $this->applyBonusMember($member, $model);
        
        /** update status deposit */
        $model->updateAttributes([
            'status' => 1
        ]);

        /** cash back distributor */
        if ($model->id_paket == Paket::DISTRIBUTOR) {
            $cashback = 10 * $model->paket->price / 100;

            $fund = new FundPassive([
                'id_member' => $member->id,
                'credit' => $cashback,
                'id_trx' => $model->id_transaksi,
                'id_fund_ref' => FundRef::CASHBACK
            ]);
    
            $fund->save();
        }

        Yii::$app->session->setFlash('success', 'Success');

        if (Session::isDistributor()) {
            return $this->redirect(['deposit/index-distributor']);
        }

        return $this->redirect(['deposit/index']);
    }

    protected function applyBonusMember(Member $member, Deposit $deposit)
    {
        $omzet = $member->paket->price;

        $bonus = [
            1 => 20 * $omzet / 100,
            2 => 5 * $omzet / 100,
            3 => 2 * $omzet / 100,
            4 => 2 * $omzet / 100,
            5 => 1 * $omzet / 100,
        ];

        foreach ($bonus as $key => $val) {

            /** bonus level */
            $upline = Downline::getUpperLevel($member->id, $key);
            if ($upline == null or $upline->isAdmin()) {
                return;
            }

            $fund = new FundActive([
                'id_member' => $upline->id,
                'credit' => $val,
                'id_trx' => $deposit->id_transaksi,
                'id_fund_ref' => FundRef::LEVEL
            ]);

            $fund->save();
        }
    }

}
