<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Mode;
use app\components\Session;
use app\models\FundPassive;
use app\models\FundRef;
use app\models\Member;
use app\models\Paket;
use app\models\Roi;
use app\widgets\Alert;
use app\widgets\UplonAlert;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'ROI';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]);

?>


<div class="row">
    <div class="col-md-6">
        <div class="card-box tilebox-one">
            <i class="ti-reload float-right m-0 h2 text-success"></i>
            <h6 class="text-muted text-uppercase mt-0">Rate</h6>
            <h3 class="my-3 card-poin">0.60 %</h3>
            <span>Terakhir diperbarui 25 Aug 2023</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3">ROI PROGESS</h4>
            <?php 
            $myRoi = 0;
            $queryRoi = FundPassive::getTotal(Session::getIdMember(), FundRef::ROI);
            if ($queryRoi != null and $queryRoi > 0) {
                $myRoi = number_format($queryRoi, 0, ",", ".");
            }

            $persenRoi = 0;
            $countRoi = FundPassive::getCount(Session::getIdMember(), FundRef::ROI);
            if ($countRoi != null and $countRoi > 0) {
                $persenRoi = $countRoi / Roi::MAX * 100;
            } 
            ?>
            <h3 class="text my-3">IDR. <?= $myRoi ?>
                <span class="text-danger float-right"><b><?= number_format($persenRoi, 2, ",", ".") ?>%</b></span>
            </h3>
            <div class="progress progress-xl" style="height: 20px;">
                <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" 
                    style="width: <?= $persenRoi ?>%">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="container-fluid">
        <div class="dt-button-wrapper">
        </div>

        <div class="member-index card-box shadow mb-4">
            <div class="mb-4">
                <h4 class="header-title" style="">
                    <?= $this->title ?>
                </h4>
            </div>
            <div class="table-responsive">
                <?= \app\widgets\DataTables::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover table-bordered'],
                'clientOptions' => [
                'dom' => 'lfrtipB',
                'buttons' => ['copy', 'csv', 'excel', 'pdf', 'print']
                ],
                'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'text-align:left; width: 10px'],    
                        ],
                        [
                        'attribute' => 'date_created',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return date("d M Y", strtotime($model->date_created));
                        },
                        'headerOptions' => ['style' => 'text-align:left; width: 205px'],
                        'contentOptions' => ['style' => 'text-align:left'],
                        ],
                        [
                        'attribute' => 'credit',
                        'format' => 'raw',
                        'header' => 'Roi',
                        'value' => function ($model) {
                            return "IDR. " . number_format($model->credit, 0, ",", ".");
                        },
                        'headerOptions' => ['style' => 'text-align:left;'],
                        'contentOptions' => ['style' => 'text-align:left'],
                        ],
                        [
                            'attribute' => 'id',
                            'header' => 'status',
                            'format' => 'html',
                            'headerOptions' => ['style' => 'text-align:left; width: 150px'],
                            'value' => function ($model) {
                                return '<span class="badge badge-pill badge-success" style="padding: 4px 16px;">SUCCESS</span>';                                
                            }
                        ],
                ],
                ]);?>
            </div>
        </div>
    </div>
</div>
