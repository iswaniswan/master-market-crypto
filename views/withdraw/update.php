<?php

/* @var $this yii\web\View */
/* @var $model app\models\Withdraw */
/* @var $referrer string */

$this->title = 'Edit Withdraw';
$this->params['breadcrumbs'][] = ['label' => 'Withdraws', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="withdraw-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
