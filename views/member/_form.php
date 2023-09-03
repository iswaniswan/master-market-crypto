<?php

use app\components\Mode;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
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
            'label' => 'col-2',
            'wrapper' => 'col',
            'error' => '',
            'hint' => '',
            'field' => 'mb-3 row',
        ],
        'options' => ['style' => 'padding:unset'],
        'inputOptions' => $inputOptions,
    ]
]); ?>

<div class="row">
    <div class="container-fluid">
        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3"><?= $this->title ?></h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($model) ?>

                    <?= $form->field($model, 'id_user')->textInput() ?>

<?= $form->field($model, 'id_paket')->textInput() ?>

<?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'no_ktp')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'id_reff_kotakab')->textInput() ?>

<?= $form->field($model, 'id_reff_provinsi')->textInput() ?>

<?= $form->field($model, 'kotakab')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'provinsi')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'kodepos')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'rekening')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'rekening_an')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'refferal_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'id_member_sponsor')->textInput() ?>

<?= $form->field($model, 'id_member_upline')->textInput() ?>

<?= $form->field($model, 'photo')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'is_verified')->textInput() ?>

<?= $form->field($model, 'is_active')->textInput() ?>

<?= $form->field($model, 'date_active')->textInput() ?>

<?= $form->field($model, 'date_created')->textInput() ?>

<?= $form->field($model, 'is_deleted')->textInput() ?>

                </div>
                <?= Html::hiddenInput('referrer', $referrer) ?>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="container-fluid">
        <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['/member'], ['class' => 'btn btn-info mb-1']) ?>
        <?php if ($mode == 'view') { ?>
            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
        <?php } else { ?>
            <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords($mode) .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
        <?php } ?>
    </div>
</div>

<?php ActiveForm::end(); ?>