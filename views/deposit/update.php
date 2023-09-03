<?php

/* @var $this yii\web\View */
/* @var $model app\models\Deposit */
/* @var $referrer string */

$this->title = 'Edit Deposit';
$this->params['breadcrumbs'][] = ['label' => 'Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="deposit-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
