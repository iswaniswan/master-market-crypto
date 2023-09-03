<?php

use app\models\Member;
use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Daftar Downline';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Member'    ],
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
                        ['class' => 'yii\grid\SerialColumn',],
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
                            'attribute' => 'id_paket',
                            'format' => 'raw',
                            'header' => 'Paket',
                            'value' => function ($model) {
                                if (@$model->paket == null) {
                                    $html = <<<html
                                        <span class="badge badge-pill badge-secondary" style="padding: 4px 8px;">INACTIVE</span>
                                    html;

                                    return $html;
                                }
                                return strtoupper(@$model->paket->name);
                            },
                            'headerOptions' => ['style' => 'text-align:left;'],
                            'contentOptions' => ['style' => 'text-align:left'],
                        ], 
                        [
                            'attribute' => 'nilai_omzet',
                            'format' => 'raw',
                            'header' => 'Nilai Omzet',
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