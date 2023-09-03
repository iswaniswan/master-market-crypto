<?php

namespace app\controllers;

use app\components\Helper;
use app\models\Reward;
use Yii;
use app\components\Mode;
use app\components\Session;
use app\models\FundPassive;
use app\models\FundRef;
use app\models\RewardClaimed;
use app\models\RewardClaimedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* custom controller, theme uplon integrated */
/**
 * RewardClaimedController implements the CRUD actions for RewardClaimed model.
 */
class RewardClaimedController extends Controller
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
     * Lists all RewardClaimed models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RewardClaimedSearch();
        $searchModel->idGroups = Session::getIdGroupAsAdminGroup();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RewardClaimed model.
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

    public function actionViewClaimed($id)
    {
        $referrer = $this->request->referrer;

        $model = $this->findModel($id);
        $reward = Reward::findOne(['id' => $model->id_reward]);

        return $this->render('view-claimed', [
            'model' => $model,
            'reward' => $reward,
            'referrer' => $referrer,
        ]);
    }

    /**
     * Creates a new RewardClaimed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new RewardClaimed();

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

    public function actionCreateClaim($id_reward, $id_member)
    {
        if ($id_reward == null or $id_member == null) {
            Yii::$app->session->setFlash('error', 'Error, hubungi Admin');
            return $this->redirect(['reward-claimed']);
        }        

        $referrer = Yii::$app->request->referrer;

        $model = new RewardClaimed();
        $model->id_member = $id_member;
        $model->id_reward = $id_reward;

        $isClaimed = RewardClaimed::findOne([
            'id_member' => $id_member,
            'id_reward' => $id_reward,
            'status' => RewardClaimed::SUCCESS
        ]);

        if ($isClaimed == null and $model->save()) {
            Yii::$app->session->setFlash('success', 'Create success.');
            return $this->redirect($referrer);
        }

        return $this->render('view', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => Mode::CREATE
        ]);
    }

    /**
     * Updates an existing RewardClaimed model.
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
     * Deletes an existing RewardClaimed model.
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
     * Finds the RewardClaimed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return RewardClaimed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RewardClaimed::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionApprove($id) 
    {
        $model = $this->findModel($id);

        $model->updateAttributes([
            'status' => 1
        ]);

        $fund = new FundPassive([
            'id_member' => $model->id_member,
            'credit' => @$model->reward->type,
            'id_trx' => Helper::generateNomorTransaksi(),
            'id_fund_ref' => FundRef::REWARD
        ]);

        $fund->save();

        Yii::$app->session->setFlash('success', 'Success');

        return $this->redirect(['reward-claimed/index']);
    }

}
