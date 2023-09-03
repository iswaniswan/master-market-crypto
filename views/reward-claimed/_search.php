<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RewardClaimedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reward-claimed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
        <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_reward') ?>

    <?= $form->field($model, 'id_member') ?>

    <?= $form->field($model, 'date_created') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'id_trx') ?>

    <div class="col-sm-2 col-xs-12">
        <?= Html::submitButton('<i class="fa fa-check"></i> Filter Data', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
