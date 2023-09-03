<?php

namespace app\controllers;

use app\components\Helper;
use app\models\FundActive;
use Yii;
use app\components\Mode;
use app\components\Session;
use app\models\FundPassive;
use app\models\FundPassiveSearch;
use app\models\FundRef;
use app\models\Member;
use app\models\Paket;
use app\models\Roi;
use app\models\RoiSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/* custom controller, theme uplon integrated */
/**
 * RoiController implements the CRUD actions for Roi model.
 */
class RoiController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => [
                                'cron',
                            ],
                            'allow' => true,
                            'roles' => ['?', '@']
                        ],
                        [
                            'actions' => [
                                'index-fund',
                            ],
                            'allow' => true,
                            'roles' => ['@']
                        ],
                        [
                            'actions' => [
                                'index-distributor',
                            ],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return Session::isDistributor();
                            }
                        ],
                        [
                            'actions' => ['index', 'index-admin', 'index-history', 'index-history-daily'],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return Session::isAdmin();
                            }
                        ],
                    ]
                ],
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
     * Lists all Roi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RoiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexHistory()
    {
        $searchModel = new RoiSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexHistoryDaily()
    {
        $searchModel = new FundPassiveSearch();
        $searchModel->id_fund_ref = FundRef::ROI;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-history-daily', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexAdmin()
    {
        $lastModel = Roi::find()->orderBy(['date_created' => SORT_DESC])->one();

        $model = new Roi();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('index-admin', [
            'model' => $model,
            'lastModel' => $lastModel,
            'referrer' => $referrer,
            'mode' => Mode::CREATE
        ]);
    }

    public function actionIndexDistributor()
    {
        $lastModel = Roi::find()->orderBy(['date_created' => SORT_DESC])->one();

        $model = new Roi();

        $searchModel = new FundPassiveSearch();
        $searchModel->id_fund_ref = FundRef::ROI;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-distributor', [
            'model' => $model,
            'lastModel' => $lastModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Roi model.
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
     * Creates a new Roi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Roi();

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
     * Updates an existing Roi model.
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
     * Deletes an existing Roi model.
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
     * Finds the Roi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Roi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Roi::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCron()
    {
        date_default_timezone_set('Asia/Jakarta');

        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        /** ROI */
        $_message = [];
        $count = 0;

        /** get all active distributor */
        $allDistributor = Member::find()->where([
            'id_paket' => Paket::DISTRIBUTOR,
            'is_active' => Member::ACTIVE
        ])->all();

        /** get rate ROI */
        $lastRoi = Roi::find()->orderBy(['date_created' => SORT_DESC])->one();
        $rate = $lastRoi->roi;
        
        $paket = Paket::findOne(['id' => Paket::DISTRIBUTOR]);

        $roiValue = $rate * $paket->price /100;

        $today = date('Y-m-d');

        foreach ($allDistributor as $member) {
            if ($member->isAdmin()) {
                continue;
            }

            $fundRoi = FundPassive::find()->where([
                'id_member' => $member->id,
                'id_fund_ref' => FundRef::ROI
            ])->andFilterWhere([
                'like', 'date_created', $today
            ]);

            // $command = $fundRoi->createCommand()->getRawSql();
            // var_dump($command); die();
            // echo $today; 
            // var_dump($fundRoi->one()); die();

            if ($fundRoi->one() != null) {
                continue;
            }

            $fundPassive = new FundPassive([
                'id_member' => $member->id,
                'id_fund_ref' => FundRef::ROI,
                'credit' => $roiValue,
                'id_trx' => Helper::generateNomorTransaksi()
            ]);

            if ($fundPassive->save()) {
                $count = $count +1;

                $_message[] = "$member->nama get $roiValue";
            } else {
                $_message[] = $fundPassive->errors;
            }
        }

        $response['status'] = true;
        $response['message'] = 'Cron Successfully executed';
        $response['data'] = $_message;
        
        return $response;
    }

}
