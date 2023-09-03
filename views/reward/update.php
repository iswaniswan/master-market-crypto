<?php

/* @var $this yii\web\View */
/* @var $model app\models\Reward */
/* @var $referrer string */

$this->title = 'Edit Reward';
$this->params['breadcrumbs'][] = ['label' => 'Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="reward-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
