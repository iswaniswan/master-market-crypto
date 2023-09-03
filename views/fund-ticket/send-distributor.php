<?php

use app\assets\Select2Asset;
use app\components\Mode;
use app\components\Session;
use app\models\FundTicket;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\FundTicket */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */
/* @var $mode Mode */

$this->title = 'Send Ticket';
$this->params['breadcrumbs'][] = ['label' => 'Send Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => "Tickets"
    ],
]) ?>

<?php 

$maxTicket = FundTicket::getBalance($member->id);

?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-12',
            'wrapper' => 'col-12',
            'error' => '',
            'hint' => '',
            'field' => 'mb-3 row',
        ],
        'options' => ['style' => 'padding:unset'],
    ],
    'enableClientScript' => false
]); ?>

<?php 



?>

<div class="row reverse-md">    
    <div class="col-md-6">
        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3"><?= $this->title ?></h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($model) ?>

                    <div class="mb-3 row field-id_member_ref" style="padding:unset">
                        <label class="col-12" for="id_member_ref">Cari Member</label>
                        <div class="col-12">
                            <select id="id_member_ref" class="form-control select2" name="FundTicket[id_member_ref]" required="required" style="text-transform:uppercase"></select>
                        <div class="valid-feedback "></div>
                        </div>
                    </div>

                    <?= $form->field($model, 'debet')->textInput([
                        'type' => 'number',
                        'value' => 1,
                        'min' => 1,
                        'max' => $maxTicket
                    ])->label('Ticket') ?>
                    
                    <div class="row" style="padding:unset">
                        <div class="col-12">
                            <span class="text-muted font-italic" style="font-size: 11px;">
                                Maksimal <?= $maxTicket ?> Ticket
                            </span>                            
                        </div>
                    </div>

                    <?= $form->field($model, 'id_member')->hiddenInput([
                        'readonly' => 'readonly',
                        'options' => ['class' => 'inputFileHidden']
                    ])->label(false) ?>

                </div>
                <?= Html::hiddenInput('referrer', $referrer) ?>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="container-fluid">
        <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['/fund-ticket/index-distributor'], ['class' => 'btn btn-info mb-1']) ?>
        <?php if ($mode == Mode::READ) { ?>
            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
        <?php } else { ?>
            <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords('Send') .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
        <?php } ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php 
/** load library */
Select2Asset::register($this);

$urlGetListMemberRef = Url::to(['/member/get-list-member-group']);
$id_group = Session::getIdGroupAsAdminGroup();

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();


$script = <<<JS
    $('#id_member_ref').select2({
        width: "100%",
        allowClear: true,
        maximumSelectionLength: 2,
        ajax: {
            type: 'GET',
            url: "{$urlGetListMemberRef}",
            dataType: "json",
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                    id_group: '{$id_group}'
                };
                return query;
            },
            processResults: function (data) {
                console.log(data);
                return {
                    results: data.data,
                };
            },
            cache: false,
        }
    });
JS;

$this->registerJs($script, View::POS_END);


$style = <<<CSS
    @media screen and (max-width: 767px) {
        .reverse-md {
            flex-direction: column-reverse;
        }
    }
CSS;

$this->registerCss($style);

?>