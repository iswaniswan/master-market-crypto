<?php

namespace app\controllers;

use app\models\AssetsSearch;
use app\models\FundRef;
use app\models\FundTicket;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Downline;
use app\models\Groups;
use app\models\Member;
use app\models\Role;
use yii\web\Cookie;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'main-error'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return Response
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }
        return $this->redirect(['/dashboard/index']);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        $area = 'PUSAT';

        $this->layout = 'main-login';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {

            if ($model->login()) {
                return $this->actionIndex();
            }

            Yii::$app->session->setFlash('error', 'Invalid login');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
            'area' => $area
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect([
            'site/login',
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister($params=[])
    {
        $referral = Yii::$app->request->get('referral');
        $area = 'PUSAT';
        if ($referral != null and strtoupper($referral) != $area) {

            try {
                $memberSponsor = Member::findOne([
                    'referral_code' => $referral,
                    'is_active' => Member::ACTIVE
                ]);

                if ($memberSponsor != null) {
                    /** cari area berdasarkan member referral */
                    if ($memberSponsor->isDistributor()) {
                        $memberAdminGroup = $memberSponsor;
                    } else {
                        $sponsorGroup = Groups::findMemberGroup($memberSponsor);                    
                        $sponsorGroupAdmin = Groups::findAdminGroup($sponsorGroup->id_group);
                        $memberAdminGroup = Member::findOne(['id' => $sponsorGroupAdmin->id_member]);
                    }
                    $area = $memberAdminGroup->kotakabs->nama . " - " . $memberAdminGroup->kotakabs->keterangan;
                } else {
                    if (strtolower($referral) != 'pusat') {
                        Yii::$app->session->setFlash('warning', 'Kode Referral tidak ditemukan');
                        $referral = null;
                    }
                }

            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

        $this->layout = 'main-register';
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            // var_dump($model); die();

            $preRegistration = true;

            /** check referral code */
            if (strtolower($model->registered_referral_code) == 'pusat') {
                /** referral is Admin */
                $admin = User::findOne([
                    'id_role' => Role::ADMIN
                ]);
                $memberSponsor = Member::findOne([
                    'id_user' => $admin->id
                ]);                

            } else {                
                $memberSponsor = Member::findOne([
                    'referral_code' => $model->registered_referral_code,
                    'is_active' => 1
                ]);
            }

            if ($memberSponsor == null) {
                Yii::$app->session->setFlash('error', 'Invalid Referral Code');
                $preRegistration = false;
            }

            /** check email exist */
            $isEmailExists = User::findOne(['email' => $model->email]);
            if ($isEmailExists != null) {
                Yii::$app->session->setFlash('error', 'Email Already Exists');
                $preRegistration = false;
            }

            /** check username exists */
            $isUsernameExists = User::findOne(['username' => $model->username]);
            if ($isUsernameExists != null) {
                Yii::$app->session->setFlash('error', 'Username Already Exists');
                $preRegistration = false;
            }

            if ($preRegistration) {
                $plain_password = $model->password;
                $hashPassword = Yii::$app->getSecurity()->generatePasswordHash($plain_password);
                $model->password = $hashPassword;
                $model->pin = strval(rand(100000, 999999));
                $model->id_role = Role::MEMBER;

                if ($model->save()) {                    
                    if ($memberSponsor->isDistributor()) {
                        $id_group = $memberSponsor->getGroupAsAdmin()->id_group;
                    } else {
                        if ($memberSponsor->isAdmin()) {
                            $id_group = Groups::GROUP_ADMIN;
                        } else {
                            $id_group = $memberSponsor->groups->id_group;
                        }
                    }

                    $availableSlot = Downline::findAvailableSlot($memberSponsor);

                    $memberUpline = Member::findOne([
                        'id' => $availableSlot['id_parent']
                    ]);
        
                    /** create base member */
                    $newMember = new Member([
                        'id_user' => $model->id,
                        'nama' => $model->nama,
                        'id_member_sponsor' => $memberSponsor->id,
                        'id_member_upline' => $memberUpline->id,
                        'phone' => $model->phone,
                        'bank' => $model->bank,
                        'rekening' => $model->rekening,
                        'rekening_an' => $model->rekening_an,
                        'date_active' => date('Y-m-d'),
                        'is_active' => 1,
                    ]);
                    $newMember->save();       
        
                    /** create base downline */
                    $downline = new Downline([
                        'id_member' => $newMember->id,
                        'id_sponsor' => $memberSponsor->id,
                        'posisi' => $availableSlot['position'],
                        'id_upline0' => $memberUpline->id,
                        'id_group' => $id_group
                    ]);
                    if (!$downline->save()) {
                        var_dump($downline->errors); die();
                    }

                    /** create base group */
                    $group = new Groups([
                        'id_group' => $id_group,
                        'id_member' => $newMember->id,
                        'is_admin_group' => 0
                    ]);
                    if (!$group->save()) {
                        var_dump($group->errors); die();
                    }

                    /** auto fund */
                    $fundTicket = new FundTicket([
                        'id_member' => $newMember->id,
                        'credit' => 50,
                        'id_fund_ref' => FundRef::TOPUP,
                        'id_member_ref' => 0
                    ]);
                    $fundTicket->save();
    
                    /** autologin model */
                    $login = new LoginForm([
                        'username' => $model->username,
                        'password' => $plain_password
                    ]);
    
                    $login->login();
    
                    Yii::$app->session->setFlash('success', 'Registration success');
                    /** registration success, then send email */
                    //$model->sendEmail();
                    return $this->actionIndex();
                } 
            } 
            Yii::$app->session->setFlash('error', 'An error occured when registration');
        }

        $model->password = '';
        return $this->render('register', [
            'model' => $model,
            'area' => $area,
            'referral' => $referral
        ]);
    }

    public function actionToggleDarkMode($flag=true)
    {
        $referrer = $this->request->referrer;

        /** cookie check */
        $cookie = new Cookie([
            'name' => 'dark-mode',
            'value' => !$flag,
            'expire' => time() + 3600, // Cookie expiration time (in seconds)
        ]);

        Yii::$app->response->cookies->add($cookie);

        return $this->redirect($referrer);
    }

    public function actionLandingPage()
    {
        $this->layout = 'main-landing';

        /** top 5 Assets */
        $searchModel = new AssetsSearch();
        $searchModel->top20 = true;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('landing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
