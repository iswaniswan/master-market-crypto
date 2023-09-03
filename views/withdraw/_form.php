<?php

use app\components\Mode;
use app\components\Session;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Withdraw */
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

                    <?php // $form->field($model, 'id_transaksi')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'created_at')->textInput()->label('Tanggal') ?>

                    <?php // $form->field($model, 'tipe')->textInput() ?>

                    <?= $form->field($model, 'id_member')->textInput([
                            'value' => @$model->member->nama
                    ]) ?>

                    <?= $form->field($model, 'amount')->textInput([
                        'value' => 'IDR. ' . number_format($model->amount, 0, ",", ".")
                    ]) ?>

                    <?= $form->field($model, 'fee')->textInput([
                        'value' => 'IDR. ' . number_format($model->fee, 0, ",", ".")
                    ]) ?>

                    <?= $form->field($model, 'nett')->textInput([
                        'value' => 'IDR. ' . number_format($model->nett, 0, ",", ".")
                    ]) ?>

                    <div class="mb-3 row field-withdraw-status" style="padding:unset">
                        <label class="col-2" for="withdraw-status">Status</label>
                        <div class="col">
                            <?= $model->getBadgeStatus() ?>                            
                        </div>
                    </div>


                    <?php // $form->field($model, 'updated_at')->textInput() ?>

                    <?php // $form->field($model, 'deleted_at')->textInput() ?>

                </div>
                <?= Html::hiddenInput('referrer', $referrer) ?>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="container-fluid">
        <?php if (Session::isAdmin()) { ?>
            <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['index-admin'], ['class' => 'btn btn-info mb-1']) ?>
        <?php } else { ?>
            <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['index-distributor'], ['class' => 'btn btn-info mb-1']) ?>
        <?php } ?>

        
        <?php /*
        <?php if ($mode == Mode::READ) { ?>
            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
        <?php } else { ?>
            <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords('update') .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
        <?php } ?>
        */ ?>
    </div>
</div>

<?php ActiveForm::end(); ?>