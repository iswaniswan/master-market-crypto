<?php


/* @var $this yii\web\View */
/* @var $model app\models\RewardClaimed */
/* @var $referrer string */

$this->title = 'Tambah Reward Claimed';
$this->params['breadcrumbs'][] = ['label' => 'Reward Claimeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-claimed-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>