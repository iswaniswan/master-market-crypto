<?php

use app\models\Member;
use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Daftar Downline Distributor';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Downline Distributor'    
    ],
]) ?>

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
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-hover table-bordered'],
                        'clientOptions' => [
                        'dom' => 'lfrtipB',
                        'buttons' => ['copy', 'csv', 'excel', 'pdf', 'print']
                        ],
                        'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'username',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return @$model->user->username;
                            },
                            'headerOptions' => ['style' => 'text-align:left;'],
                            'contentOptions' => ['style' => 'text-align:left'],
                            ],
                        [
                            'attribute' => 'id',
                            'format' => 'raw',
                            'header' => 'Kota',
                            'value' => function ($model) {
                                if (@$model->kotakabs != null) {
                                    return @$model->kotakabs->nama . ", " .$model->kotakabs->keterangan;
                                }
                                return "";
                            },
                            'headerOptions' => ['style' => 'text-align:left;'],
                            'contentOptions' => ['style' => 'text-align:left'],
                        ],
                        [
                            'attribute' => 'nilai_omzet',
                            'format' => 'raw',
                            'header' => 'Omzet',
                            'value' => function ($model) {
                                return "IDR. " . number_format($model->getTotalNilaiOmzet(), 0, ",", ".");
                            },
                            'headerOptions' => ['style' => 'text-align:left;'],
                            'contentOptions' => ['style' => 'text-align:left'],
                        ], 
                        [
                            'attribute' => 'date_active',
                            'format' => 'raw',
                            'header' => 'Tanggal Aktif',
                            'value' => function(Member $model) {
                                if (@$model->is_active != Member::ACTIVE) {
                                    $html = <<<html
                                        <span class="badge badge-pill badge-secondary" style="padding: 4px 8px;">INACTIVE</span>
                                    html;

                                    return $html;
                                }

                                return date('d M Y', strtotime(@$model->date_active));
                            },
                            'headerOptions' => ['style' => 'text-align:left;'],
                            'contentOptions' => ['style' => 'text-align:left'],
                        ],
                        [
                            'attribute' => 'id',
                            'format' => 'raw',
                            'header' => 'Tree',
                            'value' => function ($model) {
                                $url = Url::to(['member/index-member-binary-tree-read-only',
                                    'id_member_binary' => $model->id,
                                    'id_member_root' => $model->id
                                ]);
                                $html = <<<HTML
                                    <a href="$url" class="btn btn-warning btn-xs">
                                        <i class="ti ti-shine" style="font-size: smaller"></i>
                                    </a>
                                HTML;
                                return $html;

                            },
                            'headerOptions' => ['style' => 'text-align:left;'],
                            'contentOptions' => ['style' => 'text-align:left'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{toggle} {topup} {cashback}',
                            'visibleButtons' => ['toggle' => true, 'topup' => true, 'cashback' => true],
                            'buttons' => [
                                'toggle' => function ($url, $model) {
                                    $text = "Aktivasi akun distributor";
                                    $icon = "<i class='ti-check text-white' style='font-size: 10px'></i>";
                                    $iconText = "Activate";
                                    $class = 'btn btn-xs btn-success';
                                    if ($model->is_active == true) {
                                        $text = "Non Aktifkan akun distributor";
                                        $icon = "<i class='ti-close text-white' style='font-size: 10px'></i>";
                                        $iconText = "Deactivate";
                                        $class = 'btn btn-xs btn-danger';
                                    }

                                    return Html::a($iconText, ['toggle-distributor', 'id' => @$model->id], 
                                        [
                                            'title' => $text, 
                                            'data-confirm' => Yii::t('yii', $text), 
                                            'data-method'  => 'post',
                                            'class' => $class
                                        ]);                                    
                                },
                                'topup' => function ($url, $model) {
                                    $class = 'btn btn-xs btn-secondary';
                                    $url = 'javascript:void(0)';
                                    if ($model->is_active == true) {
                                        $class = 'btn btn-xs btn-success';
                                        $url = Url::to(['fund-ticket/create-distributor', 'id_member' => $model->id]);
                                    }
                                    
                                    $html = <<<HTML
                                    <a class="$class" href="$url" title="Topup">Tickets</a>
                                    HTML;
                                    return $html;                                        
                                },
                                'cashback' => function ($url, $model) {
                                    /** jika bukan distributor */
                                    if (!$model->isDistributor()) {
                                        return 'NOT DISTRIBUTOR';
                                    }

                                    $class = 'btn btn-xs btn-info disabled';
                                    $url = 'javascript:void(0)';
                                    if ($model->isDoneCashback() == false) {
                                        $class = 'btn btn-xs btn-info';
                                        $url = Url::to(['fund-passive/cashback-distributor', 'id_member' => $model->id]);
                                    }                                    
                                    $html = <<<HTML
                                    <a class="$class" 
                                        href="$url" title="Cashback" data-confirm="Konfirmasi Cashback 10%" data-method="post">Cashback</a>
                                    HTML;
                                    return $html;                                        
                                },
                            ],
                        ],   
                        
                        
                    ],
                ]);?>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<<JS
    const dtPrint = () => {
        const dtBtn = $('.btn.buttons-print');
        dtBtn.trigger('click');
    }
    const dtExportPdf = () => {
        const dtBtn = $('.btn.buttons-pdf.buttons-html5');
        dtBtn.trigger('click');
    }
    const dtExportExcel = (e) => {
        const dtBtn = $('.btn.buttons-excel.buttons-html5');
        dtBtn.trigger('click');
    }
JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>