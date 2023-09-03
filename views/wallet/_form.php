<?php

use app\components\Mode;
use app\components\Session;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use app\assets\Select2Asset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Wallet */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */
/* @var $mode Mode */


$inputOptions = [];
if (@$mode == Mode::READ) {
    $inputOptions = ['disabled' => true];
}

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
        'inputOptions' => $inputOptions,
    ],
    'enableClientScript' => false
]); ?>

<div class="row">
    <div class="container-fluid">
        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3">Fund USDT</h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($model) ?>

                    <div class="mb-3 row field-id_member" style="padding:unset">
                        <label class="col-12" for="id_member">Cari Member</label>
                        <div class="col-12">
                            <select id="id_member" class="form-control select2" name="Wallet[id_member]" required="required" style="text-transform:uppercase"></select>
                            <div class="valid-feedback "></div>
                        </div>
                    </div>

                    <?php // $form->field($model, 'id_member')->textInput() ?>

                    <div class="row field-wallet-balance" style="padding:unset">
                        <label class="col-12" for="wallet-balance">Fund</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">USDT</span>
                                </div>
                                <input type="text" class="form-control" id="wallet-balance" name="Wallet[balance]" required>
                            </div>
                        </div>
                    </div>

                    <?= $form->field($model, 'id_crypto')->hiddenInput([
                        'value' => \app\models\Wallet::USDT,
                        'readonly' => true
                    ])->label(false) ?>

                </div>
                <?= Html::hiddenInput('referrer', $referrer) ?>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="container-fluid">
        <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['index'], ['class' => 'btn btn-info mb-1']) ?>
        <?php if ($mode == Mode::READ) { ?>
            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
        <?php } else { ?>
            <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords('Submit') .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
        <?php } ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php

/** load library */
Select2Asset::register($this);

$urlGetListMemberRef = Url::to(['/member/get-list-member']);
$id_group = Session::getIdGroupAsAdminGroup();

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();

$script = <<<JS
    $('#id_member').select2({
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
