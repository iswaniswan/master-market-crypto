<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RewardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Settings Reward';
$this->params['breadcrumbs'][] = $this->title;

\app\assets\XeditableAsset::register($this);

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Settings Reward'
    ],
]) ?>

<div class="row mb-4">
    <div class="container-fluid">
        <div class="dt-button-wrapper">
            <?= Html::a('<i class="ti-plus mr-2"></i> Add', ['create'], ['class' => 'btn btn-primary mb-1']) ?>
            <?php /*
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
            */ ?>
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
                            'attribute' => 'terms',
                            'format' => 'raw',
                            'header' => 'Nilai Omzet (Kiri & Kanan)',
                            'value' => function ($model) {
                                $value = number_format(@$model->terms, 0, ",", ".");

                                $html = <<<HTML
                                    <a href="javascript:void(0);" class="x-edit" data-name="terms" data-value="{$model->terms}" data-pk="{$model->id}">
                                        <span>IDR. {$value}</span>
                                    </a>
                                HTML;

                                return $html;
                            },
                            'headerOptions' => ['style' => 'text-align:left;'],
                            'contentOptions' => ['style' => 'text-align:left'],
                        ],
                                    [
                        'attribute' => 'type',
                        'format' => 'raw',
                        'header' => 'Nilai Reward',
                        'value' => function ($model) {
                            $value = number_format(@$model->type, 0, ",", ".");

                            $html = <<<HTML
                                <a href="javascript:void(0);" class="x-edit" data-name="type" data-value="{$model->type}" data-pk="{$model->id}">
                                    <span>IDR. {$value}</span>
                                </a>
                            HTML;

                            return $html;
                        },
                        'headerOptions' => ['style' => 'text-align:left;'],
                        'contentOptions' => ['style' => 'text-align:left'],
                        ],
                                     [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'visibleButtons' => ['view' => false, 'update' => false, 'delete' => false],
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<i class="ti-eye"></i>', ['view', 'id' => @$model->id], ['title' => 'Detail', 'data-pjax' => '0']);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<i class="ti-pencil"></i>', ['update', 'id' => @$model->id], ['title' => 'Detail', 'data-pjax' => '0']);
                            },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="ti-trash"></i>', ['delete', 'id' => @$model->id],['title' => 'Delete', 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'), 'data-method'  => 'post']);
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
$urlEditable = \yii\helpers\Url::to(['reward/editable']);
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();

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
    
    $.fn.editableform.buttons = `<button type="button" class="btn btn-primary btn-xs editable-submit">
            <i class="ti-check"></i>
        </button>
        <button type="button" class="btn btn-danger btn-xs editable-cancel">
            <i class="ti-close"></i>
        </button>`;
    
    $(document).ready(function() {
        
        $('.x-edit').each(function() {
            const t = $(this);
            
            t.editable({
                url: "{$urlEditable}",
                type: "text",
                pk: t.data('pk'),
                displayValue: "IDR. " + t.data('value'),
                name: t.data('name'),
                title: "Edit Nilai",
                mode: "inline",
                inputclass: "form-control-sm",
                display: function(value, response) {
                    const newValue = response?.data?.displayValue;
                    $(this).text(newValue);
                },
                ajaxOptions: {
                    type: 'post',
                    headers: {
                        '{$csrfParam}': '{$csrfToken}'
                    }
                },
                 success: function(response) {
                    console.log(response);
                    if (response?.status != 'success') {
                        alert(response?.message);
                    }
                }, 
            });
            
        });
        
    });
JS;

$this->registerJs($script, \yii\web\View::POS_END);

/** !important
 override x-editable class
 */

$urlImgLoading = Yii::getAlias('@web').'/images/loading.gif';
$urlImgClear = Yii::getAlias('@web').'/images/clear.png';
$style = <<<CSS
    .editableform-loading {
        background: url('{$urlImgLoading}') center center no-repeat !important;  
    }
    .editable-clear-x {
       background: url('{$urlImgClear}') center center no-repeat !important;
    }

CSS;

$this->registerCss($style);

?>