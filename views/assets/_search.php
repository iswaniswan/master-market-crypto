<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AssetsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assets-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
        <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'asset_id') ?>

    <?= $form->field($model, 'rank') ?>

    <?= $form->field($model, 'symbol') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'supply') ?>

    <?php // echo $form->field($model, 'max_supply') ?>

    <?php // echo $form->field($model, 'market_cap_usd') ?>

    <?php // echo $form->field($model, 'volume_usd_24_hr') ?>

    <?php // echo $form->field($model, 'price_usd') ?>

    <?php // echo $form->field($model, 'change_percent_24_hr') ?>

    <?php // echo $form->field($model, 'vwap_24_Hr') ?>

    <?php // echo $form->field($model, 'explorer') ?>

    <div class="col-sm-2 col-xs-12">
        <?= Html::submitButton('<i class="fa fa-check"></i> Filter Data', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
