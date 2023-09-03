<?php

namespace app\controllers;

use app\components\Coincap;
use app\components\Session;
use app\models\Wallet;
use app\models\WalletSearch;
use Yii;
use app\components\Mode;
use app\models\Crypto;
use app\models\CryptoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/* custom controller, theme uplon integrated */
/**
 * CryptoController implements the CRUD actions for Crypto model.
 */
class CryptoController extends Controller
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
     * Lists all Crypto models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Session::isAdmin() == false) {
            return $this->actionIndexMember();
        }

        $searchModel = new CryptoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexMember()
    {
        $searchModel = new CryptoSearch();
        $searchModel->status = 1;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Crypto model.
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

    protected function updateAssetsHistory($id_crypto)
    {
        $crypto = Crypto::findOne([
            'id' => $id_crypto
        ]);
        $asset_id = $crypto->assets->asset_id;

        $coincap = new Coincap();
        $results = $coincap->getAssetsHistory($asset_id, $interval='d1');

        return $results;
    }

    protected function getDataChart($id_crypto)
    {
        $crypto = Crypto::findOne([
            'id' => $id_crypto
        ]);
        $asset_id = $crypto->assets->asset_id;

        $coincap = new Coincap();
        $results = $coincap->getAssetsHistory($asset_id, $interval='d1');

        return $results;
    }

    public function actionSwap($id)
    {
        $referrer = $this->request->referrer;

        $dataChart = $this->getDataChart($id);

        /** wallet */
        $searchModel = new WalletSearch();
        $searchModel->id_member = Session::getIdMember();
        $searchModel->grouping = true;
        $dataProvider = $searchModel->search($this->request->queryParams);

        $model = $this->findModel($id);

        if ((Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $id_crypto = $post['id_crypto'];

            /** market beli */
            $marketBeli = @$post['MarketBeli'];
            if (@$marketBeli['balance'] > 0) {
                $balance = $marketBeli['balance'];
                $return = $marketBeli['return'];
                $return = str_replace(",", ".", $return);
                /** validasi wallet */
                $validBalance = Wallet::getBalance(Session::getIdMember(), Wallet::USDT);
                if ($validBalance > $balance) {
                    $walletOut = new Wallet([
                        'id_member' => Session::getIdMember(),
                        'balance' => 0 - $balance,
                        'id_crypto' => Wallet::USDT,
                        'date_updated' => date('Y-m-d H:i:s')
                    ]);

                    $walletIn = new Wallet([
                        'id_member' => Session::getIdMember(),
                        'balance' => $return,
                        'id_crypto' => $id_crypto,
                        'date_updated' => date('Y-m-d H:i:s')
                    ]);

                    if ($walletOut->save() and $walletIn->save()) {
                        Yii::$app->session->setFlash('success', 'Swap success.');
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Invalid Balance');
                }

                return $this->redirect(['/crypto/swap', 'id' => $id_crypto]);
            }


            /** market jual */
            $marketJual = @$post['MarketJual'];
            if (@$marketJual['balance'] > 0) {
                $balance = $marketJual['balance'];
                $return = $marketJual['return'];
                $return = str_replace(",", ".", $return);
                /** validasi wallet */
                $validBalance = Wallet::getBalance(Session::getIdMember(), $id_crypto);
                if ($validBalance > $balance) {
                    $walletOut = new Wallet([
                        'id_member' => Session::getIdMember(),
                        'balance' => 0 - $balance,
                        'id_crypto' => $id_crypto,
                        'date_updated' => date('Y-m-d H:i:s')
                    ]);

                    $walletIn = new Wallet([
                        'id_member' => Session::getIdMember(),
                        'balance' => $return,
                        'id_crypto' => Wallet::USDT,
                        'date_updated' => date('Y-m-d H:i:s')
                    ]);

                    if ($walletOut->save() and $walletIn->save()) {
                        Yii::$app->session->setFlash('success', 'Swap success.');
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Invalid Balance');
                }

                return $this->redirect(['/crypto/swap', 'id' => $id_crypto]);
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('swap', [
            'model' => $model,
            'referrer' => $referrer,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataChart' => $this->getDummyDataChart()
        ]);
    }

    /**
     * Creates a new Crypto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Crypto();

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
     * Updates an existing Crypto model.
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
     * Deletes an existing Crypto model.
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
     * Finds the Crypto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Crypto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Crypto::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionToggleStatus($id) {
        $model = $this->findModel($id);
        $model->updateAttributes([
            'status' => !$model->status
        ]);

        Yii::$app->session->setFlash('success', 'Update success');

        return $this->redirect(['/crypto/index']);
    }

    public function actionEditable()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {

            $post = Yii::$app->request->post();
            $id = $post['pk'];
            $name = $post['name'];
            $value = $post['value'];

            if ($id != '' and intval($value) > 0) {
                $model = $this->findModel($id);
                $model->$name = $value;

                if ($model->save()) {
                    return [
                        'status' => 'success',
                        'message' => 'Form data received successfully.',
                        'data' => [
                            'value' => $value,
                            'displayValue' => "IDR. " . number_format($value, 0, ",", ".")
                        ]
                    ];
                }

                $response['message'] = 'Invalid value';
            }
        }

        return $response;
    }

    private function getDummyDataChart()
    {
            $arrayVar = [
                "data" => [
                    [
                        "priceUsd" => "19812.5100475392891408",
                        "time" => 1662249600000,
                        "date" => "2022-09-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19796.8944810940855867",
                        "time" => 1662336000000,
                        "date" => "2022-09-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19611.4637935699748953",
                        "time" => 1662422400000,
                        "date" => "2022-09-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "18913.4396739802198835",
                        "time" => 1662508800000,
                        "date" => "2022-09-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19266.0458479421839061",
                        "time" => 1662595200000,
                        "date" => "2022-09-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20741.4859866784575116",
                        "time" => 1662681600000,
                        "date" => "2022-09-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21447.6165593487974651",
                        "time" => 1662768000000,
                        "date" => "2022-09-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21670.0629506041114859",
                        "time" => 1662854400000,
                        "date" => "2022-09-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22140.1641476837807858",
                        "time" => 1662940800000,
                        "date" => "2022-09-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21554.8801000666828668",
                        "time" => 1663027200000,
                        "date" => "2022-09-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20238.7856873031113938",
                        "time" => 1663113600000,
                        "date" => "2022-09-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20008.2773012904009055",
                        "time" => 1663200000000,
                        "date" => "2022-09-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19720.5705226823934893",
                        "time" => 1663286400000,
                        "date" => "2022-09-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19957.3325120549506732",
                        "time" => 1663372800000,
                        "date" => "2022-09-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19888.1371171612926995",
                        "time" => 1663459200000,
                        "date" => "2022-09-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19026.3833494791095574",
                        "time" => 1663545600000,
                        "date" => "2022-09-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19195.9916600450500350",
                        "time" => 1663632000000,
                        "date" => "2022-09-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19020.6069503083210076",
                        "time" => 1663718400000,
                        "date" => "2022-09-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "18986.7018653814808726",
                        "time" => 1663804800000,
                        "date" => "2022-09-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19064.1533363209492234",
                        "time" => 1663891200000,
                        "date" => "2022-09-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19078.0794554055902371",
                        "time" => 1663977600000,
                        "date" => "2022-09-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "18992.0396875489801316",
                        "time" => 1664064000000,
                        "date" => "2022-09-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19016.7516009444234207",
                        "time" => 1664150400000,
                        "date" => "2022-09-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19736.9532421797387537",
                        "time" => 1664236800000,
                        "date" => "2022-09-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19125.8870731421173706",
                        "time" => 1664323200000,
                        "date" => "2022-09-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19397.5819239754464354",
                        "time" => 1664409600000,
                        "date" => "2022-09-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19530.3777649587614644",
                        "time" => 1664496000000,
                        "date" => "2022-09-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19334.8725635547788566",
                        "time" => 1664582400000,
                        "date" => "2022-10-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19241.9778915758551072",
                        "time" => 1664668800000,
                        "date" => "2022-10-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19324.0782159715121715",
                        "time" => 1664755200000,
                        "date" => "2022-10-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19945.9448979267178359",
                        "time" => 1664841600000,
                        "date" => "2022-10-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20131.2846550523810728",
                        "time" => 1664928000000,
                        "date" => "2022-10-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20155.9610630358586302",
                        "time" => 1665014400000,
                        "date" => "2022-10-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19774.7313006156171094",
                        "time" => 1665100800000,
                        "date" => "2022-10-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19498.4041693131315871",
                        "time" => 1665187200000,
                        "date" => "2022-10-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19453.8379179464143741",
                        "time" => 1665273600000,
                        "date" => "2022-10-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19333.8980915412074451",
                        "time" => 1665360000000,
                        "date" => "2022-10-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19077.3916663255568526",
                        "time" => 1665446400000,
                        "date" => "2022-10-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19124.3447192473265667",
                        "time" => 1665532800000,
                        "date" => "2022-10-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19040.5274020395029791",
                        "time" => 1665619200000,
                        "date" => "2022-10-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19522.1250019535211336",
                        "time" => 1665705600000,
                        "date" => "2022-10-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19148.9780133345209363",
                        "time" => 1665792000000,
                        "date" => "2022-10-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19175.0524491263283648",
                        "time" => 1665878400000,
                        "date" => "2022-10-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19402.7803495392384055",
                        "time" => 1665964800000,
                        "date" => "2022-10-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19480.9355675661438511",
                        "time" => 1666051200000,
                        "date" => "2022-10-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19224.3127923764012537",
                        "time" => 1666137600000,
                        "date" => "2022-10-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19131.3987163543915992",
                        "time" => 1666224000000,
                        "date" => "2022-10-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19074.1774229364805013",
                        "time" => 1666310400000,
                        "date" => "2022-10-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19185.0092264516167929",
                        "time" => 1666396800000,
                        "date" => "2022-10-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19273.6624737742414778",
                        "time" => 1666483200000,
                        "date" => "2022-10-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19360.5842930465872547",
                        "time" => 1666569600000,
                        "date" => "2022-10-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19598.0309295911540323",
                        "time" => 1666656000000,
                        "date" => "2022-10-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20531.3679926951811212",
                        "time" => 1666742400000,
                        "date" => "2022-10-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20601.6409759486035653",
                        "time" => 1666828800000,
                        "date" => "2022-10-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20359.4042796880354166",
                        "time" => 1666915200000,
                        "date" => "2022-10-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20741.9299929694182376",
                        "time" => 1667001600000,
                        "date" => "2022-10-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20672.2340879343718005",
                        "time" => 1667088000000,
                        "date" => "2022-10-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20456.5596285592344065",
                        "time" => 1667174400000,
                        "date" => "2022-10-31T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20472.1854281803112043",
                        "time" => 1667260800000,
                        "date" => "2022-11-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20399.6155561646073703",
                        "time" => 1667347200000,
                        "date" => "2022-11-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20250.1591803548350168",
                        "time" => 1667433600000,
                        "date" => "2022-11-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20694.6433290564384457",
                        "time" => 1667520000000,
                        "date" => "2022-11-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21330.9819087437022765",
                        "time" => 1667606400000,
                        "date" => "2022-11-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21188.7187550902073263",
                        "time" => 1667692800000,
                        "date" => "2022-11-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20769.0494134645551845",
                        "time" => 1667779200000,
                        "date" => "2022-11-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19582.6847651580144378",
                        "time" => 1667865600000,
                        "date" => "2022-11-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17538.3449424558036564",
                        "time" => 1667952000000,
                        "date" => "2022-11-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16934.4287552411898093",
                        "time" => 1668038400000,
                        "date" => "2022-11-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17098.7605327906309625",
                        "time" => 1668124800000,
                        "date" => "2022-11-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16842.5945556563574774",
                        "time" => 1668211200000,
                        "date" => "2022-11-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16640.7849412521283567",
                        "time" => 1668297600000,
                        "date" => "2022-11-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16453.4217244244621199",
                        "time" => 1668384000000,
                        "date" => "2022-11-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16828.1672686912073947",
                        "time" => 1668470400000,
                        "date" => "2022-11-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16703.8171409685389271",
                        "time" => 1668556800000,
                        "date" => "2022-11-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16620.2153319408650432",
                        "time" => 1668643200000,
                        "date" => "2022-11-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16751.8747293145155800",
                        "time" => 1668729600000,
                        "date" => "2022-11-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16665.5511428700634781",
                        "time" => 1668816000000,
                        "date" => "2022-11-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16614.2902593119747018",
                        "time" => 1668902400000,
                        "date" => "2022-11-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16048.3177567236080100",
                        "time" => 1668988800000,
                        "date" => "2022-11-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "15958.3820130996895998",
                        "time" => 1669075200000,
                        "date" => "2022-11-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16485.9187904522945220",
                        "time" => 1669161600000,
                        "date" => "2022-11-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16622.1894904812232011",
                        "time" => 1669248000000,
                        "date" => "2022-11-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16521.7335921461074262",
                        "time" => 1669334400000,
                        "date" => "2022-11-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16595.8634477601480042",
                        "time" => 1669420800000,
                        "date" => "2022-11-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16583.2706419399947885",
                        "time" => 1669507200000,
                        "date" => "2022-11-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16244.4534804249545108",
                        "time" => 1669593600000,
                        "date" => "2022-11-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16426.6885772042469151",
                        "time" => 1669680000000,
                        "date" => "2022-11-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16908.1554317547941481",
                        "time" => 1669766400000,
                        "date" => "2022-11-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17068.7558958508773389",
                        "time" => 1669852800000,
                        "date" => "2022-12-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16979.2857154681463734",
                        "time" => 1669939200000,
                        "date" => "2022-12-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16988.9090018478421108",
                        "time" => 1670025600000,
                        "date" => "2022-12-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17035.0925339865127925",
                        "time" => 1670112000000,
                        "date" => "2022-12-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17183.7492810601715129",
                        "time" => 1670198400000,
                        "date" => "2022-12-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17003.0485272968686125",
                        "time" => 1670284800000,
                        "date" => "2022-12-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16889.1151779391588089",
                        "time" => 1670371200000,
                        "date" => "2022-12-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16937.9499116534686494",
                        "time" => 1670457600000,
                        "date" => "2022-12-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17193.9737621260110491",
                        "time" => 1670544000000,
                        "date" => "2022-12-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17170.0958141196951979",
                        "time" => 1670630400000,
                        "date" => "2022-12-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17171.4980561972468888",
                        "time" => 1670716800000,
                        "date" => "2022-12-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17006.7732991872958240",
                        "time" => 1670803200000,
                        "date" => "2022-12-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17469.7795971678925501",
                        "time" => 1670889600000,
                        "date" => "2022-12-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17874.3288044258164737",
                        "time" => 1670976000000,
                        "date" => "2022-12-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17584.9892534183185969",
                        "time" => 1671062400000,
                        "date" => "2022-12-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17100.9581465020192511",
                        "time" => 1671148800000,
                        "date" => "2022-12-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16712.7345315771766130",
                        "time" => 1671235200000,
                        "date" => "2022-12-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16741.1680932984506440",
                        "time" => 1671321600000,
                        "date" => "2022-12-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16678.6984115228012963",
                        "time" => 1671408000000,
                        "date" => "2022-12-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16791.0409093138384856",
                        "time" => 1671494400000,
                        "date" => "2022-12-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16829.3254816647506394",
                        "time" => 1671580800000,
                        "date" => "2022-12-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16778.5203664230674199",
                        "time" => 1671667200000,
                        "date" => "2022-12-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16832.0619010377835603",
                        "time" => 1671753600000,
                        "date" => "2022-12-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16847.3340092993588763",
                        "time" => 1671840000000,
                        "date" => "2022-12-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16830.1880855379784060",
                        "time" => 1671926400000,
                        "date" => "2022-12-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16844.3498426510507567",
                        "time" => 1672012800000,
                        "date" => "2022-12-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16797.2820711821713093",
                        "time" => 1672099200000,
                        "date" => "2022-12-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16629.7329808697507183",
                        "time" => 1672185600000,
                        "date" => "2022-12-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16585.8723812486913877",
                        "time" => 1672272000000,
                        "date" => "2022-12-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16545.3440912153305752",
                        "time" => 1672358400000,
                        "date" => "2022-12-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16570.2696789707556758",
                        "time" => 1672444800000,
                        "date" => "2022-12-31T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16708.5235619029337193",
                        "time" => 1672617600000,
                        "date" => "2023-01-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16682.7065037970623473",
                        "time" => 1672704000000,
                        "date" => "2023-01-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16822.1913689397671344",
                        "time" => 1672790400000,
                        "date" => "2023-01-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16826.7335356303742298",
                        "time" => 1672876800000,
                        "date" => "2023-01-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16821.1406834381729146",
                        "time" => 1672963200000,
                        "date" => "2023-01-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16940.3135199784003800",
                        "time" => 1673049600000,
                        "date" => "2023-01-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "16946.4596188209993873",
                        "time" => 1673136000000,
                        "date" => "2023-01-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17235.6387252169897770",
                        "time" => 1673222400000,
                        "date" => "2023-01-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17290.6166407638340421",
                        "time" => 1673308800000,
                        "date" => "2023-01-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "17450.3811902371874028",
                        "time" => 1673395200000,
                        "date" => "2023-01-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "18387.8057268659231833",
                        "time" => 1673481600000,
                        "date" => "2023-01-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19078.0542150749676115",
                        "time" => 1673568000000,
                        "date" => "2023-01-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20822.4369410666000944",
                        "time" => 1673654400000,
                        "date" => "2023-01-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20781.6306135608689749",
                        "time" => 1673740800000,
                        "date" => "2023-01-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21039.9934023215035695",
                        "time" => 1673827200000,
                        "date" => "2023-01-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21194.6297632302541402",
                        "time" => 1673913600000,
                        "date" => "2023-01-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21130.6419853517859985",
                        "time" => 1674000000000,
                        "date" => "2023-01-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20857.1538817527808806",
                        "time" => 1674086400000,
                        "date" => "2023-01-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21312.6767534919714857",
                        "time" => 1674172800000,
                        "date" => "2023-01-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22905.3238260502978185",
                        "time" => 1674259200000,
                        "date" => "2023-01-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22836.3026850250024713",
                        "time" => 1674345600000,
                        "date" => "2023-01-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22850.9921739384306811",
                        "time" => 1674432000000,
                        "date" => "2023-01-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22985.8084693994132959",
                        "time" => 1674518400000,
                        "date" => "2023-01-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22730.6485808201823212",
                        "time" => 1674604800000,
                        "date" => "2023-01-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23088.5098671765254327",
                        "time" => 1674691200000,
                        "date" => "2023-01-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23017.3320728634933905",
                        "time" => 1674777600000,
                        "date" => "2023-01-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23054.9270036340027835",
                        "time" => 1674864000000,
                        "date" => "2023-01-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23466.2119977578957375",
                        "time" => 1674950400000,
                        "date" => "2023-01-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23300.2866318598568753",
                        "time" => 1675036800000,
                        "date" => "2023-01-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22976.6004059012269312",
                        "time" => 1675123200000,
                        "date" => "2023-01-31T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23179.3083202506026742",
                        "time" => 1675209600000,
                        "date" => "2023-02-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23828.2216365205844973",
                        "time" => 1675296000000,
                        "date" => "2023-02-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23492.9958440372951291",
                        "time" => 1675382400000,
                        "date" => "2023-02-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23409.4810370035294084",
                        "time" => 1675468800000,
                        "date" => "2023-02-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23232.9036208624388465",
                        "time" => 1675555200000,
                        "date" => "2023-02-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22924.2368911184825371",
                        "time" => 1675641600000,
                        "date" => "2023-02-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22995.8487290578448537",
                        "time" => 1675728000000,
                        "date" => "2023-02-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23112.7585795352107081",
                        "time" => 1675814400000,
                        "date" => "2023-02-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22551.4951913173097458",
                        "time" => 1675900800000,
                        "date" => "2023-02-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21788.5584488319158371",
                        "time" => 1675987200000,
                        "date" => "2023-02-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21727.3331103581609534",
                        "time" => 1676073600000,
                        "date" => "2023-02-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21891.3797070960673801",
                        "time" => 1676160000000,
                        "date" => "2023-02-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21702.9411390997440961",
                        "time" => 1676246400000,
                        "date" => "2023-02-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21914.2403789466732169",
                        "time" => 1676332800000,
                        "date" => "2023-02-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22698.0459580625934285",
                        "time" => 1676419200000,
                        "date" => "2023-02-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24597.1983157315813915",
                        "time" => 1676505600000,
                        "date" => "2023-02-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24020.8260930378926334",
                        "time" => 1676592000000,
                        "date" => "2023-02-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24636.1610669125561656",
                        "time" => 1676678400000,
                        "date" => "2023-02-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24668.9695359304846563",
                        "time" => 1676764800000,
                        "date" => "2023-02-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24676.3913365553320310",
                        "time" => 1676851200000,
                        "date" => "2023-02-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24710.0462740622626737",
                        "time" => 1676937600000,
                        "date" => "2023-02-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24039.3029946541346101",
                        "time" => 1677024000000,
                        "date" => "2023-02-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24138.4791091483587500",
                        "time" => 1677110400000,
                        "date" => "2023-02-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23641.4457356037117396",
                        "time" => 1677196800000,
                        "date" => "2023-02-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23071.2316343487363312",
                        "time" => 1677283200000,
                        "date" => "2023-02-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23272.6866329534744479",
                        "time" => 1677369600000,
                        "date" => "2023-02-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23451.3470445222886365",
                        "time" => 1677456000000,
                        "date" => "2023-02-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23381.8110685735819774",
                        "time" => 1677542400000,
                        "date" => "2023-02-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23601.3632399048713792",
                        "time" => 1677628800000,
                        "date" => "2023-03-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23438.1242321208543515",
                        "time" => 1677715200000,
                        "date" => "2023-03-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22436.8049408222792152",
                        "time" => 1677801600000,
                        "date" => "2023-03-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22351.0769388173958120",
                        "time" => 1677888000000,
                        "date" => "2023-03-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22467.7826318359252603",
                        "time" => 1677974400000,
                        "date" => "2023-03-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22432.5623115674822113",
                        "time" => 1678060800000,
                        "date" => "2023-03-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22335.6066766164592654",
                        "time" => 1678147200000,
                        "date" => "2023-03-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "22055.9034326894028710",
                        "time" => 1678233600000,
                        "date" => "2023-03-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "21425.2646013291470877",
                        "time" => 1678320000000,
                        "date" => "2023-03-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "19983.6698261968326937",
                        "time" => 1678406400000,
                        "date" => "2023-03-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20329.5672997548433460",
                        "time" => 1678492800000,
                        "date" => "2023-03-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "20769.2947272662881028",
                        "time" => 1678579200000,
                        "date" => "2023-03-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "23040.5587498913665632",
                        "time" => 1678665600000,
                        "date" => "2023-03-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24840.7243072815564397",
                        "time" => 1678752000000,
                        "date" => "2023-03-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24673.4779862102744374",
                        "time" => 1678838400000,
                        "date" => "2023-03-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "24715.8530051771088349",
                        "time" => 1678924800000,
                        "date" => "2023-03-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26352.5950513818312013",
                        "time" => 1679011200000,
                        "date" => "2023-03-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27438.8247996796080818",
                        "time" => 1679097600000,
                        "date" => "2023-03-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27543.0004464391805503",
                        "time" => 1679184000000,
                        "date" => "2023-03-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27936.3990369168166971",
                        "time" => 1679270400000,
                        "date" => "2023-03-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28013.0215528835403520",
                        "time" => 1679356800000,
                        "date" => "2023-03-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28110.0063638245765001",
                        "time" => 1679443200000,
                        "date" => "2023-03-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27852.3989402204516404",
                        "time" => 1679529600000,
                        "date" => "2023-03-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28022.2571610933227939",
                        "time" => 1679616000000,
                        "date" => "2023-03-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27546.3280591413568020",
                        "time" => 1679702400000,
                        "date" => "2023-03-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27773.3025226432706098",
                        "time" => 1679788800000,
                        "date" => "2023-03-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27581.5636020432474443",
                        "time" => 1679875200000,
                        "date" => "2023-03-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27065.6706570567305529",
                        "time" => 1679961600000,
                        "date" => "2023-03-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28118.2122209636066111",
                        "time" => 1680048000000,
                        "date" => "2023-03-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28415.3047378586822899",
                        "time" => 1680134400000,
                        "date" => "2023-03-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28240.9604785536354686",
                        "time" => 1680220800000,
                        "date" => "2023-03-31T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28503.4131407150636311",
                        "time" => 1680307200000,
                        "date" => "2023-04-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28348.9536914941676999",
                        "time" => 1680393600000,
                        "date" => "2023-04-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28022.5329918991818367",
                        "time" => 1680480000000,
                        "date" => "2023-04-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28109.3957987353541417",
                        "time" => 1680566400000,
                        "date" => "2023-04-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28388.4814692914645986",
                        "time" => 1680652800000,
                        "date" => "2023-04-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28069.0058759817607304",
                        "time" => 1680739200000,
                        "date" => "2023-04-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27990.5747619648380118",
                        "time" => 1680825600000,
                        "date" => "2023-04-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28089.6538928897815714",
                        "time" => 1680912000000,
                        "date" => "2023-04-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28140.6397728016233982",
                        "time" => 1680998400000,
                        "date" => "2023-04-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28645.1442691189378943",
                        "time" => 1681084800000,
                        "date" => "2023-04-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30144.7338213132436418",
                        "time" => 1681171200000,
                        "date" => "2023-04-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30039.7781101534723216",
                        "time" => 1681257600000,
                        "date" => "2023-04-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30292.1687100302474579",
                        "time" => 1681344000000,
                        "date" => "2023-04-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30650.6777384198965982",
                        "time" => 1681430400000,
                        "date" => "2023-04-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30434.8639371663871582",
                        "time" => 1681516800000,
                        "date" => "2023-04-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30393.8554698417893432",
                        "time" => 1681603200000,
                        "date" => "2023-04-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29735.3273542551182441",
                        "time" => 1681689600000,
                        "date" => "2023-04-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29980.3234918104349582",
                        "time" => 1681776000000,
                        "date" => "2023-04-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29599.2320278270652858",
                        "time" => 1681862400000,
                        "date" => "2023-04-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28693.5871089640283995",
                        "time" => 1681948800000,
                        "date" => "2023-04-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27989.4523414564250888",
                        "time" => 1682035200000,
                        "date" => "2023-04-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27514.0917220757787835",
                        "time" => 1682121600000,
                        "date" => "2023-04-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27697.6193626705667123",
                        "time" => 1682208000000,
                        "date" => "2023-04-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27571.8147575613960808",
                        "time" => 1682294400000,
                        "date" => "2023-04-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27583.3504320693820584",
                        "time" => 1682380800000,
                        "date" => "2023-04-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28875.2734589306436740",
                        "time" => 1682467200000,
                        "date" => "2023-04-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29169.9905443212808708",
                        "time" => 1682553600000,
                        "date" => "2023-04-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29360.8496777050860638",
                        "time" => 1682640000000,
                        "date" => "2023-04-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29400.2775133618514419",
                        "time" => 1682726400000,
                        "date" => "2023-04-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29482.7793309489858169",
                        "time" => 1682812800000,
                        "date" => "2023-04-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28480.6684927539377623",
                        "time" => 1682899200000,
                        "date" => "2023-05-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28305.9784040535584856",
                        "time" => 1682985600000,
                        "date" => "2023-05-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28610.5412483375626674",
                        "time" => 1683072000000,
                        "date" => "2023-05-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29021.6970269700923502",
                        "time" => 1683158400000,
                        "date" => "2023-05-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29307.2147017422079682",
                        "time" => 1683244800000,
                        "date" => "2023-05-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29274.1760660042434561",
                        "time" => 1683331200000,
                        "date" => "2023-05-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29043.8697650102163587",
                        "time" => 1683417600000,
                        "date" => "2023-05-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28012.1958023564619499",
                        "time" => 1683504000000,
                        "date" => "2023-05-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27673.4221659808497250",
                        "time" => 1683590400000,
                        "date" => "2023-05-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27762.6623411514198371",
                        "time" => 1683676800000,
                        "date" => "2023-05-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27307.2347387629391758",
                        "time" => 1683763200000,
                        "date" => "2023-05-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26523.1284778230188642",
                        "time" => 1683849600000,
                        "date" => "2023-05-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26896.0815183706594169",
                        "time" => 1683936000000,
                        "date" => "2023-05-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26926.7558515533275456",
                        "time" => 1684022400000,
                        "date" => "2023-05-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27339.9820751478798675",
                        "time" => 1684108800000,
                        "date" => "2023-05-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27081.4480564020283787",
                        "time" => 1684195200000,
                        "date" => "2023-05-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27011.0568221391760637",
                        "time" => 1684281600000,
                        "date" => "2023-05-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27155.4262555060583744",
                        "time" => 1684368000000,
                        "date" => "2023-05-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26873.9850308907029237",
                        "time" => 1684454400000,
                        "date" => "2023-05-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26955.6689536055746336",
                        "time" => 1684540800000,
                        "date" => "2023-05-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26987.7819028325305641",
                        "time" => 1684627200000,
                        "date" => "2023-05-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26818.8643382866466116",
                        "time" => 1684713600000,
                        "date" => "2023-05-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27253.2635210140161639",
                        "time" => 1684800000000,
                        "date" => "2023-05-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26628.1357072600056109",
                        "time" => 1684886400000,
                        "date" => "2023-05-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26305.3007797090265223",
                        "time" => 1684972800000,
                        "date" => "2023-05-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26578.9597929258862306",
                        "time" => 1685059200000,
                        "date" => "2023-05-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26728.6316336335670061",
                        "time" => 1685145600000,
                        "date" => "2023-05-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27305.3452724645652638",
                        "time" => 1685232000000,
                        "date" => "2023-05-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27862.8275891200714598",
                        "time" => 1685318400000,
                        "date" => "2023-05-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27799.5587363620457328",
                        "time" => 1685404800000,
                        "date" => "2023-05-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27236.2075146675573982",
                        "time" => 1685491200000,
                        "date" => "2023-05-31T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26934.8017157384688131",
                        "time" => 1685577600000,
                        "date" => "2023-06-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27090.6745121911658697",
                        "time" => 1685664000000,
                        "date" => "2023-06-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27178.7232129039627469",
                        "time" => 1685750400000,
                        "date" => "2023-06-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27189.6598551795523701",
                        "time" => 1685836800000,
                        "date" => "2023-06-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26458.1576646589126630",
                        "time" => 1685923200000,
                        "date" => "2023-06-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26129.3111591056609014",
                        "time" => 1686009600000,
                        "date" => "2023-06-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26720.3553223378340561",
                        "time" => 1686096000000,
                        "date" => "2023-06-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26495.8343757687501609",
                        "time" => 1686182400000,
                        "date" => "2023-06-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26538.9702969847713147",
                        "time" => 1686268800000,
                        "date" => "2023-06-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25894.2170781330051201",
                        "time" => 1686355200000,
                        "date" => "2023-06-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25903.2390325000871091",
                        "time" => 1686441600000,
                        "date" => "2023-06-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25902.5208857331035753",
                        "time" => 1686528000000,
                        "date" => "2023-06-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26010.0722911230186964",
                        "time" => 1686614400000,
                        "date" => "2023-06-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25848.2449122047394167",
                        "time" => 1686700800000,
                        "date" => "2023-06-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25118.2129299484302041",
                        "time" => 1686787200000,
                        "date" => "2023-06-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25779.0451053741975048",
                        "time" => 1686873600000,
                        "date" => "2023-06-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26499.4431965284123932",
                        "time" => 1686960000000,
                        "date" => "2023-06-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26576.9131429623725830",
                        "time" => 1687046400000,
                        "date" => "2023-06-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26530.5790877017900706",
                        "time" => 1687132800000,
                        "date" => "2023-06-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27191.1124554268946774",
                        "time" => 1687219200000,
                        "date" => "2023-06-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29250.0900894396389465",
                        "time" => 1687305600000,
                        "date" => "2023-06-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30076.9881784360109979",
                        "time" => 1687392000000,
                        "date" => "2023-06-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30331.4280335901839315",
                        "time" => 1687478400000,
                        "date" => "2023-06-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30648.9290773959818333",
                        "time" => 1687564800000,
                        "date" => "2023-06-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30641.1924404527579699",
                        "time" => 1687651200000,
                        "date" => "2023-06-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30290.2076445941653384",
                        "time" => 1687737600000,
                        "date" => "2023-06-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30552.7981056875506234",
                        "time" => 1687824000000,
                        "date" => "2023-06-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30312.5439578440851164",
                        "time" => 1687910400000,
                        "date" => "2023-06-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30411.9233852278433866",
                        "time" => 1687996800000,
                        "date" => "2023-06-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30573.5636669455804196",
                        "time" => 1688083200000,
                        "date" => "2023-06-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30519.9335697349331812",
                        "time" => 1688169600000,
                        "date" => "2023-07-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30561.6882488573282100",
                        "time" => 1688256000000,
                        "date" => "2023-07-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30831.7341380762814073",
                        "time" => 1688342400000,
                        "date" => "2023-07-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30997.7300350900505459",
                        "time" => 1688428800000,
                        "date" => "2023-07-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30611.0546255291806892",
                        "time" => 1688515200000,
                        "date" => "2023-07-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30526.9556091448293380",
                        "time" => 1688601600000,
                        "date" => "2023-07-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30205.6444368176208664",
                        "time" => 1688688000000,
                        "date" => "2023-07-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30256.2745307334418754",
                        "time" => 1688774400000,
                        "date" => "2023-07-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30307.3076639148772146",
                        "time" => 1688860800000,
                        "date" => "2023-07-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30283.8862485422843191",
                        "time" => 1688947200000,
                        "date" => "2023-07-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30519.8890693370755967",
                        "time" => 1689033600000,
                        "date" => "2023-07-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30601.4490775432855811",
                        "time" => 1689120000000,
                        "date" => "2023-07-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30733.1142765322879841",
                        "time" => 1689206400000,
                        "date" => "2023-07-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "31028.6314871212227586",
                        "time" => 1689292800000,
                        "date" => "2023-07-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30357.2079754463762817",
                        "time" => 1689379200000,
                        "date" => "2023-07-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30353.9990090120557902",
                        "time" => 1689465600000,
                        "date" => "2023-07-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30213.3789577714659458",
                        "time" => 1689552000000,
                        "date" => "2023-07-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29977.0992342493234124",
                        "time" => 1689638400000,
                        "date" => "2023-07-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30011.8872166318353992",
                        "time" => 1689724800000,
                        "date" => "2023-07-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "30023.1559018313651546",
                        "time" => 1689811200000,
                        "date" => "2023-07-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29880.5254300118545855",
                        "time" => 1689897600000,
                        "date" => "2023-07-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29913.6143500216032869",
                        "time" => 1689984000000,
                        "date" => "2023-07-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29985.5024893939962183",
                        "time" => 1690070400000,
                        "date" => "2023-07-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29472.1474647005832902",
                        "time" => 1690156800000,
                        "date" => "2023-07-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29204.0492745381253884",
                        "time" => 1690243200000,
                        "date" => "2023-07-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29304.4661085451208628",
                        "time" => 1690329600000,
                        "date" => "2023-07-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29373.0641345634279093",
                        "time" => 1690416000000,
                        "date" => "2023-07-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29277.9995244746840846",
                        "time" => 1690502400000,
                        "date" => "2023-07-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29358.8160738782921644",
                        "time" => 1690588800000,
                        "date" => "2023-07-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29348.7109283422096871",
                        "time" => 1690675200000,
                        "date" => "2023-07-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29346.3547882488295488",
                        "time" => 1690761600000,
                        "date" => "2023-07-31T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29024.8796937471207170",
                        "time" => 1690848000000,
                        "date" => "2023-08-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29435.9107486407214418",
                        "time" => 1690934400000,
                        "date" => "2023-08-02T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29178.8610013741602034",
                        "time" => 1691020800000,
                        "date" => "2023-08-03T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29166.1970816610706998",
                        "time" => 1691107200000,
                        "date" => "2023-08-04T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29076.6102331162330878",
                        "time" => 1691193600000,
                        "date" => "2023-08-05T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29089.5837685994929229",
                        "time" => 1691280000000,
                        "date" => "2023-08-06T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29071.9605202236879330",
                        "time" => 1691366400000,
                        "date" => "2023-08-07T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29451.0061363304197404",
                        "time" => 1691452800000,
                        "date" => "2023-08-08T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29709.9547782023410143",
                        "time" => 1691539200000,
                        "date" => "2023-08-09T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29497.2999652918718355",
                        "time" => 1691625600000,
                        "date" => "2023-08-10T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29397.4821589846689379",
                        "time" => 1691712000000,
                        "date" => "2023-08-11T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29425.9587746108491754",
                        "time" => 1691798400000,
                        "date" => "2023-08-12T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29408.9912267162156710",
                        "time" => 1691884800000,
                        "date" => "2023-08-13T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29386.5392227606320752",
                        "time" => 1691971200000,
                        "date" => "2023-08-14T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29321.7098058078183624",
                        "time" => 1692057600000,
                        "date" => "2023-08-15T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "29112.5222954964648562",
                        "time" => 1692144000000,
                        "date" => "2023-08-16T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "28196.2269621538203570",
                        "time" => 1692230400000,
                        "date" => "2023-08-17T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26325.8736729156684410",
                        "time" => 1692316800000,
                        "date" => "2023-08-18T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26040.4555011756934265",
                        "time" => 1692403200000,
                        "date" => "2023-08-19T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26169.6683130664172812",
                        "time" => 1692489600000,
                        "date" => "2023-08-20T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26086.8708467624457058",
                        "time" => 1692576000000,
                        "date" => "2023-08-21T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25996.1678558690215052",
                        "time" => 1692662400000,
                        "date" => "2023-08-22T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26182.3974386279154810",
                        "time" => 1692748800000,
                        "date" => "2023-08-23T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26300.1973186642227508",
                        "time" => 1692835200000,
                        "date" => "2023-08-24T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26066.1381557210542763",
                        "time" => 1692921600000,
                        "date" => "2023-08-25T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26061.5780512258384801",
                        "time" => 1693008000000,
                        "date" => "2023-08-26T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26103.9787671058646142",
                        "time" => 1693094400000,
                        "date" => "2023-08-27T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26047.4301031244520170",
                        "time" => 1693180800000,
                        "date" => "2023-08-28T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26708.7388216107508114",
                        "time" => 1693267200000,
                        "date" => "2023-08-29T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "27362.3057766821912755",
                        "time" => 1693353600000,
                        "date" => "2023-08-30T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "26887.8176361001758242",
                        "time" => 1693440000000,
                        "date" => "2023-08-31T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25912.0303471176786984",
                        "time" => 1693526400000,
                        "date" => "2023-09-01T00:00:00.000Z",
                    ],
                    [
                        "priceUsd" => "25878.3858105495857011",
                        "time" => 1693612800000,
                        "date" => "2023-09-02T00:00:00.000Z",
                    ],
                ],
                "timestamp" => 1693739050137,
            ];

            return $arrayVar;
    }

}
