<?php

use app\models\Deposit;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DepositSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Daftar Deposit Member';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Deposit'    ],
]) ?>

<div class="row mb-4">
    <div class="container-fluid">
        <div class="dt-button-wrapper">
            <?= Html::a('<i class="ti-printer mr-2"></i> Print', ['#'], ['class' => 'btn btn-info mb-1', 'onclick' => 'dtPrint()' ]) ?>
            <div class="btn-group mr-1">
                <?= Html::a('<i class="ti-download mr-2"></i> Export', ['#'], [
                    'class' => 'btn btn-success mb-1 dropdown-toggle',
                    'data-toggle' => 'dropdown'
                ]) ?>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <?= Html::a('Excel', ['#'], ['class' => 'dropdown-item', 'onclick' => 'dtExportExcel()']) ?>
                    <?= Html::a('Pdf', ['#'], ['class' => 'dropdown-item', 'onclick' => 'dtExportPdf()']) ?>
                </div>
            </div>
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
                                'attribute' => 'id_user',
                                'header' => 'Username',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return @$model->user->username;
                                },
                                'headerOptions' => ['style' => 'text-align:left;'],
                                'contentOptions' => ['style' => 'text-align:left'],
                                ],
                                [
                                'attribute' => 'id_paket',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return strtoupper(@$model->paket->name);
                                },
                                'headerOptions' => ['style' => 'text-align:left;'],
                                'contentOptions' => ['style' => 'text-align:left'],
                                ],
                                            [
                                'attribute' => 'id_ref_metode_pembayaran',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return ucwords(@$model->refMetodePembayaran->nama);
                                },
                                'headerOptions' => ['style' => 'text-align:left;'],
                                'contentOptions' => ['style' => 'text-align:left'],
                                ],
                                            [
                                'attribute' => 'total_bayar',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return "IDR. " . number_format($model->total_bayar, 0, ",", ".");
                                }, 
                                'headerOptions' => ['style' => 'text-align:left;'],
                                'contentOptions' => ['style' => 'text-align:left'],
                                ],
                                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return $model->getBadgeStatusTransaksi();
                                },
                                'headerOptions' => ['style' => 'text-align:left;'],
                                'contentOptions' => ['style' => 'text-align:left'],
                                ],
                                [
                                'attribute' => 'created_at',
                                'format' => 'raw',
                                'header' => 'Tanggal Transaksi',
                                'headerOptions' => ['style' => 'text-align:left;'],
                                'contentOptions' => ['style' => 'text-align:left'],
                                ],
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {approve}',
                            'visibleButtons' => ['view' => true, 'approve' => true],
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="ti-eye"></i>', ['history-deposit', 'id' => @$model->id], ['title' => 'Detail', 'data-pjax' => '0']);
                                },
                                'approve' => function ($url, $model) {     
                                    if ($model->status == Deposit::ACTIVE) {
                                        $html = <<<HTML
                                            <a href="javascript:void(0)" disabled="disabled" title="Approve"><i class="ti-check-box text-muted"></i></a>
                                        HTML;
                                        return $html;
                                    }
                                    
                                    return Html::a('<i class="ti-check-box"></i>', [
                                        'approve', 'id' => @$model->id
                                    ], [
                                        'title' => 'Approve', 'data-pjax' => '0',
                                        'onclick' => 'return confirmApproval()'
                                    ]);
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

    const confirmApproval = () => {
        return confirm("Aktivasi Paket..");
    }

JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>