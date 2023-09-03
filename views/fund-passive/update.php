<?php

/* @var $this yii\web\View */
/* @var $model app\models\FundPassive */
/* @var $referrer string */

$this->title = 'Edit Fund Passive';
$this->params['breadcrumbs'][] = ['label' => 'Fund Passives', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="fund-passive-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
