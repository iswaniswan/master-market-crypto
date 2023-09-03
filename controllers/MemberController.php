<?php

namespace app\controllers;

use app\models\Deposit;
use app\models\FundActive;
use app\models\FundPassive;
use app\models\FundRef;
use app\models\Withdraw;
use Yii;
use app\models\Member;
use app\models\MemberSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Session;
use app\models\Downline;
use app\models\Groups;
use app\models\Kotakab;
use app\models\Paket;
use app\models\User;
use app\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\web\UploadedFile;

/* custom controller, theme uplon integrated */
/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'generate-username', 'generate-password', 'generate-pin', 'validate-referral-code'
                        ],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => [
                            'index', 'index-member', 'view', 'create', 'update', 'delete', 
                            'index-member-downline', 'index-member-downline-binary', 'index-admin-downline',
                            'index-member-binary-tree', 'index-member-binary-tree-read-only', 'create-member-downline',
                            'update-profile', 'update-paket', 'update-bank', 'update-security', 'member-area',
                            'generate-username', 'generate-password', 'generate-pin', 'index-fund-statement', 'index-fund-statement-view',
                            'index-admin-distributor', 'create-admin-distributor', 'get-list-ref-kota-kab', 'toggle-distributor',
                            'get-list-member-group', 'get-list-member'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['member-approval-distributor'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Session::isDistributor();
                        }
                    ],
                    [
                        'actions' => ['index-admin', 'index-rekap'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Session::isAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all Member models.
     *
     * @return string
     */
    public function actionIndex($id_paket=null)
    {
        $searchModel = new MemberSearch();
        if ($id_paket != null) {
            $searchModel->id_paket = @$id_paket;
        }

        $searchModel->id_group = Session::getIdGroupAsAdminGroup();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexAdminDistributor()
    {
        $isAdmin = Session::isAdmin();

        if ($isAdmin == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        
        $searchModel = new MemberSearch();
        $searchModel->id_paket = Paket::DISTRIBUTOR;
        $searchModel->id_member_sponsor = Session::getIdMember();        

        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['date_active' => SORT_DESC];

        return $this->render('index-admin-distributor', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexMemberDownline()
    {
        $isActive = Session::isMemberActive();

        if ($isActive == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        
        $member = Member::findOne(Session::getIdMember());

        // var_dump(Session::getIdGroupAsAdminGroup()); die();

        $searchModel = new MemberSearch();
        $searchModel->id_member_sponsor = $member->id;
        $searchModel->not_distributor = true;

        if (Session::isDistributor()) {
            $searchModel->id_group = Session::getIdGroupAsAdminGroup();
        } else {
            $searchModel->id_group = Session::getIdGroups();
        }

        // var_dump(Session::getIdGroupAsAdminGroup()); die();

        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['date_active' => SORT_DESC];

        return $this->render('index-member-downline', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member
        ]);
    }

    public function actionIndexMemberDownlineBinary()
    {
        $isActive = Session::isMemberActive();

        if ($isActive == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        
        $member = Member::findOne(Session::getIdMember());

        $searchModel = new MemberSearch();
        $searchModel->id_member_sponsor = $member->id;
        
        if (Session::isDistributor()) {
            $searchModel->not_distributor = true;
            $searchModel->id_group = Session::getIdGroupAsAdminGroup();
        } else {
            if (Session::isAdmin()) {
                $searchModel->not_distributor = true;
            }
            $searchModel->downline_binary = true;
            $searchModel->id_group = Session::getIdGroups();
        }

        // var_dump(Session::getIdGroupAsAdminGroup()); die();

        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['date_active' => SORT_DESC];

        return $this->render('index-member-downline', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member
        ]);
    }

    public function actionIndexAdminDownline()
    {
        $isAdmin = Session::isAdmin();

        if ($isAdmin == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        
        $member = Member::findOne(Session::getIdMember());

        $searchModel = new MemberSearch();
        // $searchModel->id_member_sponsor = $member->id;
        $searchModel->not_distributor = true;
        $searchModel->id_group = Groups::GROUP_ADMIN;

        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['date_active' => SORT_DESC];

        return $this->render('index-member-downline', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member
        ]);
    }

    public function actionIndexMemberBinaryTree($id_member_binary=null)
    {
        $isActive = Session::isMemberActive();

        if ($isActive == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        
        $id_member = Session::getIdMember();

        if ($id_member_binary != null) {
            $id_member = $id_member_binary;
        }

        $member = Member::findOne($id_member);

        $dataChartBinary = Downline::getDataChartBinaryAll($member);

        return $this->render('index-member-binary-tree', [
            'member' => $member,
            'dataChartBinary' => $dataChartBinary
        ]);
    }

    public function actionIndexMemberBinaryTreeReadOnly($id_member_binary=null, $id_member_root=null)
    {
        $isActive = Session::isMemberActive();

        if ($isActive == null) {
            return $this->redirect(['dashboard/index-member']);
        }

        $id_member = Session::getIdMember();

        if ($id_member_binary != null) {
            $id_member = $id_member_binary;
        }

        $member = Member::findOne($id_member);

        $dataChartBinary = Downline::getDataChartBinaryAll($member);

        return $this->render('index-member-binary-tree-read-only', [
            'member' => $member,
            'dataChartBinary' => $dataChartBinary,
            'idMemberRoot' => $id_member_root
        ]);
    }

    /**
     * member approval by distributor
     */
    public function actionMemberApprovalDistributor()
    {
        $id_member = Session::getIdMember();

        if ($id_member == null) {
            return $this->redirect(['dashboard/index-member']);
        }

        $member = Member::findOne($id_member);

        $searchModel = new MemberSearch();
        $searchModel->id_member_upline = $id_member;

        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index-member-binary-tree', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member
        ]);
    }
    

    /**
     * Displays a single Member model.
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
            'mode' => 'view'
        ]);
    }

    public function actionMemberArea($id)
    {
        $referrer = $this->request->referrer;
        return $this->render('index-member-area', [
            'model' => $this->findModel($id),
            'referrer' => $referrer,
        ]);
    }

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Member();

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
            'mode' => 'create'
        ]);
    }

    public function actionCreateMemberDownline($id_member_upline=null, $posisi=null, $id_group=null)
    {
        $isActive = Session::isMemberActive();

        if ($isActive == null) {
            return $this->redirect(['dashboard/index-member']);
        }

        if (($id_member_upline == null) or ($posisi == null) or ($posisi > 1) or ($id_group == null)) {
            return $this->actionIndexMemberBinaryTree();
        }        

        $memberSponsor = Member::findOne(['id' => Session::getIdMember()]);
        // $memberUpline = Member::FindAvailableMemberUpline(Session::getIdMember());
        $memberUpline = Member::findOne(['id' => $id_member_upline]);

        $model = new Member();        

        $model->id_member_sponsor = $memberSponsor->id;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            $preRegistration = true;

            /** check email exist */
            $isEmailExists = User::findOne(['email' => $model->email]);
            if ($isEmailExists != null) {
                Yii::$app->session->setFlash('error', 'Email Already Exists');
                $preRegistration = false;
            }

            /** check username exist */
            $isUsernameExists = User::findOne(['username' => $model->username]);
            if ($isUsernameExists != null) {
                Yii::$app->session->setFlash('error', 'Username Already Exists');
                $preRegistration = false;
            }

            $model->id_member_sponsor = $memberSponsor->id;
            $model->id_member_upline = $memberUpline->id;

            if ($preRegistration and $model->save()) {

                /** create user */
                $user = User::createFromMember($model);

                /** update id_user */
                $model->updateAttributes([
                    'id_user' => $user->id
                ]);

                /** create downline */
                Downline::createFromMember($model, $model->id_member_upline, $posisi, $id_group);

                /** create groups */
                $group = new Groups([
                    'id_member' => $model->id,
                    'id_group' => $id_group
                ]);
                $group->save();

                Yii::$app->session->setFlash('success', 'Create success.');
                if (Session::isAdmin() or Session::isDistributor()) {
                    return $this->actionIndexMemberDownline();
                }
                return $this->actionIndexMemberDownlineBinary();
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('create-downline', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => 'create',
            'memberSponsor' => $memberSponsor,
            'memberUpline' => $memberUpline,
            'posisi' => $posisi
        ]);
    }

    public function actionCreateAdminDistributor($id_member_upline=null, $posisi=null, $id_group=null)
    {
        $isAdmin = Session::isAdmin();

        if ($isAdmin == null) {
            return $this->redirect(['dashboard/index-member']);
        }    

        $memberAdmin = Member::findOne(['id' => Session::getIdMember()]);

        $model = new Member();        

        $model->id_member_sponsor = Session::getIdMember();
        $model->id_paket = Paket::DISTRIBUTOR;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            $preRegistration = true;

            /** check email exist */
            $isEmailExists = User::findOne(['email' => $model->email]);
            if ($isEmailExists != null) {
                Yii::$app->session->setFlash('error', 'Email Already Exists');
                $preRegistration = false;
            }

            /** check username exist */
            $isUsernameExists = User::findOne(['username' => $model->username]);
            if ($isUsernameExists != null) {
                Yii::$app->session->setFlash('error', 'Username Already Exists');
                $preRegistration = false;
            }

            $model->id_member_sponsor = Session::getIdMember();
            $model->id_member_upline = Session::getIdMember();

            if ($preRegistration and $model->save()) {

                /** create user */
                $user = User::createDistributorFromMember($model);

                /** update id_user */
                $model->updateAttributes([
                    'id_user' => $user->id
                ]);
                
                /** create referral code */        
                $randomString = Member::generateUniqueReferralCode();

                /** aktivasi member */
                $model->updateAttributes([
                    // 'is_active' => Member::ACTIVE,
                    // 'date_active' => date('Y-m-d H:i:s'),
                    'referral_code' => $randomString
                ]);  

                /** update role user */
                if ($model->isActive()) {
                    $user = $model->user;
                    $roleByPaket = $model->getRoleByPaket();
                    $user->updateAttributes([
                        'id_role' => $roleByPaket
                    ]);
                }

                /** create downline */
                // Downline::createFromMember($model, $model->id_member_upline, $posisi);

                /** create groups member of admin */
                $group = new Groups([
                    'id_group' => Session::getIdGroups(),
                    'id_member' => $model->id,
                    'is_admin_group' => 0,
                ]);
                $group->save();

                /** create groups as admin group */
                $group = new Groups([
                    'id_group' => $user->id . $model->id,
                    'id_member' => $model->id,
                    'is_admin_group' => 1,
                ]);
                $group->save();

                Yii::$app->session->setFlash('success', 'Create success.');
                return $this->actionIndexAdminDistributor();
            }

            Yii::$app->session->setFlash('error', 'An error occured when create.');
        }

        return $this->render('create-distributor', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => 'create',
            'memberAdmin' => $memberAdmin
        ]);
    }

    /**
     * Updates an existing Member model.
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
            'mode' => 'update'
        ]);
    }

    public function actionUpdateProfile($id)
    {
        $model = $this->findModel($id);
        $oldPhoto = $model->photo;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            $upload = UploadedFile::getInstanceByName('Member[photo]');
            if ($upload !== null) {
                $model->photo = $upload->baseName . Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s')) . '.' . $upload->extension;
            } else {
                $model->photo = $oldPhoto;
            }

            if ($model->save()) {

                if ($upload !== null) {
                    $path = Yii::getAlias('@app').'/web/uploads/';
                    $upload->saveAs($path.$model->photo, false);
                }

                Yii::$app->session->setFlash('success', 'Update success.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'An error occured when update.');
        }

        return $this->render('update-profile', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => 'update'
        ]);
    }

    public function actionUpdateBank($id)
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

        return $this->render('update-bank', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => 'update'
        ]);
    }

    public function actionUpdateSecurity($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        $user = User::findOne(['id' => $model->id_user]);

        if ($user->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];

            $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);

            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Update success.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'An error occured when update.');
        }

        return $this->render('update-security', [
            'model' => $model,
            'user' => $user,
            'referrer' => $referrer,
            'mode' => 'update'
        ]);
    }

    public function actionUpdatePaket($id)
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

        return $this->render('update-paket', [
            'model' => $model,
            'referrer' => $referrer,
            'mode' => 'update'
        ]);
    }

    /**
     * Deletes an existing Member model.
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

    public function actionToggleDistributor($id)
    {
        $model = $this->findModel($id);
        
        $update = $model->isActive() ? 0 : Member::ACTIVE;

        $model->updateAttributes([
            'is_active' => $update,
            'date_active' => date('Y-m-d H:i:s')
        ]);

        Yii::$app->session->setFlash('success', 'Status updated');
        return $this->redirect(['member/index-admin-distributor']);
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGenerateUsername()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $formData = Yii::$app->request->post(); 

            $email = $formData['email'];

            $username = User::generateFromEmail($email);

            return [
                'status' => 'success',
                'message' => 'Form data received successfully.',
                'data' => [
                    'username' => $username
                ]
            ];
        }

        return $response;
    }

    public function actionGeneratePassword()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {

            $password = Yii::$app->security->generateRandomString(8);

            return [
                'status' => 'success',
                'message' => 'Form data received successfully.',
                'data' => [
                    'password' => $password
                ]
            ];
        }

        return $response;
    }

    public function actionGeneratePin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {

            $pin = rand(100000, 999999);

            return [
                'status' => 'success',
                'message' => 'Form data received successfully.',
                'data' => [
                    'pin' => $pin
                ]
            ];
        }

        return $response;
    }

    public function actionIndexFundStatement($id_fund_ref=null)
    {
        $isActive = Session::isMemberActive();

        if ($isActive == null) {
            return $this->redirect(['dashboard/index-member']);
        }
        
        $member = Member::findOne(Session::getIdMember());

        $connection = Yii::$app->getDb();

        $where = '';
        if ($id_fund_ref != null and $id_fund_ref != '') {
            $where = " WHERE id_fund_ref = $id_fund_ref ";
        }

        $sql = <<<SQL
        SELECT * FROM (
            SELECT id_member, credit, debet, id_fund_ref, id_trx, date_created 
                FROM fund_active
                WHERE id_member = $member->id
            UNION ALL
            SELECT id_member, credit, debet, id_fund_ref, id_trx, date_created 
                FROM fund_passive
                WHERE id_member = $member->id
            UNION ALL
            SELECT id_member, 0 AS credit, total_bayar AS debet, 7 AS id_fund_ref, id_transaksi AS id_trx, created_at AS date_created
                FROM deposit
                WHERE id_member = $member->id
                    AND status = 1
            /*  UNION ALL
            SELECT id_member, 0 AS credit, amount AS debet, 3 AS id_fund_ref, id_transaksi AS id_trx, created_at AS date_created
                FROM withdraw
                WHERE id_member = $member->id
                    AND status = 1 */
        ) X
        $where
        ORDER BY date_created DESC
        SQL;

        $command = $connection->createCommand($sql);

        $result = $command->queryAll();

        $provider = new ActiveDataProvider([
            'query' => $command,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        return $this->render('index-fund-statement', [
            'member' => $member,
            'result' => $result,
            'dataProvider' => $provider
        ]);
    }

    public function actionGetListRefKotaKab()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->get()) {
            $keyword = Yii::$app->request->get('q');

            if ($keyword == null) {
                $keyword = '';
            }

            $out = [];

            $results = Kotakab::getList($keyword);

            foreach ($results as $key => $val) {
                $out[] = [
                    'id' => $key,
                    'text' => $val
                ];
            }
            
            return [
                'status' => 'success',
                'message' => 'Form data received successfully.',
                'data' => $out
            ];
        }

        return $response;
    }

    public function actionGetListMemberGroup()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->get()) {
            $keyword = Yii::$app->request->get('q');
            $id_group = Yii::$app->request->get('id_group');
            $id_member = Yii::$app->request->get('id_member');

            if ($keyword == null) {
                $keyword = '';
            }

            $out = [];

            $results = Member::getListMemberGroup($id_group, $keyword);

            foreach ($results as $member) {
                if ($member->isDistributor() or $member->isAdmin()) {
                    continue;
                }

                if ($member->id == @$id_member) {
                    continue;
                }

                $out[] = [
                    'id' => $member->id,
                    'text' => $member->nama
                ];
            }
            
            return [
                'status' => 'success',
                'message' => 'Form data received successfully.',
                'data' => $out
            ];
        }

        return $response;
    }

    public function actionIndexFundStatementView($id_fund_ref, $id_trx)
    {
        if ($id_fund_ref == FundRef::LEVEL) {
            $model = FundActive::getByIdTrx($id_trx);

            return $this->render('_fund', [
                'id_fund_ref' => $id_fund_ref,
                'model' => $model,
            ]);
        }

        if ($id_fund_ref == FundRef::ROI or $id_fund_ref == FundRef::CASHBACK) {
            $model = FundPassive::getByIdTrx($id_trx);

            return $this->render('_fund', [
                'id_fund_ref' => $id_fund_ref,
                'model' => $model,
            ]);
        }

        if ($id_fund_ref == FundRef::WITHDRAW) {
            $model = Withdraw::getByIdTrx($id_trx);

            return $this->render('_fund_withdraw', [
                'id_fund_ref' => $id_fund_ref,
                'model' => $model,
            ]);
        }

        if ($id_fund_ref == FundRef::DEPOSIT) {
            $model = Deposit::getByIdTrx($id_trx);

            return $this->redirect(['/deposit/history-deposit', 'id' => $model->id]);
        }

        Yii::$app->session->setFlash('danger', 'Invalid Transaction');
        return $this->redirect(['/member/index-fund-statement']);
    }

    public function actionValidateReferralCode()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {

            $referral_code = Yii::$app->request->post('referral_code');

            $query = Member::findOne(['referral_code' => $referral_code]);
            if ($query != null) {
                return [
                    'status' => 'success',
                    'message' => 'Form data received successfully.',
                    'data' => []
                ];
            }

            $response['message'] = 'Referral tidak ditemukan';

        }

        return $response;
    }

    public function actionGetListMember()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => 'error',
            'data' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->get()) {
            $keyword = Yii::$app->request->get('q');
            $id_member = Yii::$app->request->get('id_member');

            if ($keyword == null) {
                $keyword = '';
            }

            $out = [];

            $results = Member::getListMember($keyword);

            foreach ($results as $member) {
                if ($member->isDistributor() or $member->isAdmin()) {
                    continue;
                }

                if ($member->id == @$id_member) {
                    continue;
                }

                $out[] = [
                    'id' => $member->id,
                    'text' => $member->nama
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Form data received successfully.',
                'data' => $out
            ];
        }

        return $response;
    }

}
