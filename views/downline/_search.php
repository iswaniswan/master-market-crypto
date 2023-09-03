<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DownlineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="downline-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
        <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_member') ?>

    <?= $form->field($model, 'id_sponsor') ?>

    <?= $form->field($model, 'posisi') ?>

    <?= $form->field($model, 'kiri') ?>

    <?php // echo $form->field($model, 'kanan') ?>

    <?php // echo $form->field($model, 'id_upline0') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_updated') ?>

    <div class="col-sm-2 col-xs-12">
        <?= Html::submitButton('<i class="fa fa-check"></i> Filter Data', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
