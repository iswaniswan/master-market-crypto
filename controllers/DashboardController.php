<?php

namespace app\controllers;

use app\components\Session;
use app\models\AssetsSearch;
use app\models\Member;
use app\models\Paket;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class DashboardController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'index-member'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['index-distributor'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Session::isDistributor();
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

    public function actionIndex()
    {
        if (!Session::isAdmin()) {
            return $this->actionIndexMember();
        }

        /** top 5 Assets */
        $searchModel = new AssetsSearch();
        $searchModel->top5 = true;
        $dataProvider = $searchModel->search($this->request->queryParams);

        $this->layout = 'main';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexMember()
    {
        if (Session::isAdmin()) {
            return $this->actionIndex();
        }

        $this->layout = 'main';

        if (Session::getIdMember() == null or Session::isMemberActive() == false) {
            return $this->render('index-member');
        }

        $member = Member::findOne(Session::getIdMember());

        /** top 20 Assets */
        $searchModel = new AssetsSearch();
        $searchModel->top5 = true;
        $dataProvider = $searchModel->search($this->request->queryParams);
        
        return $this->render('index-member-summary', [
            'member' => $member,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);    
    }

}
