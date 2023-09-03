<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
        <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'id_paket') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'no_ktp') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'id_reff_kotakab') ?>

    <?php // echo $form->field($model, 'id_reff_provinsi') ?>

    <?php // echo $form->field($model, 'kotakab') ?>

    <?php // echo $form->field($model, 'provinsi') ?>

    <?php // echo $form->field($model, 'kodepos') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'bank') ?>

    <?php // echo $form->field($model, 'rekening') ?>

    <?php // echo $form->field($model, 'rekening_an') ?>

    <?php // echo $form->field($model, 'refferal_code') ?>

    <?php // echo $form->field($model, 'id_member_sponsor') ?>

    <?php // echo $form->field($model, 'id_member_upline') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'is_verified') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'date_active') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <div class="col-sm-2 col-xs-12">
        <?= Html::submitButton('<i class="fa fa-check"></i> Filter Data', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
