<?php

/* @var $this yii\web\View */
/* @var $model app\models\RewardClaimed */
/* @var $referrer string */

$this->title = 'Edit Reward Claimed';
$this->params['breadcrumbs'][] = ['label' => 'Reward Claimeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="reward-claimed-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
