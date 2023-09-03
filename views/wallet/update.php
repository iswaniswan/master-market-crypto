<?php

/* @var $this yii\web\View */
/* @var $model app\models\Wallet */
/* @var $referrer string */

$this->title = 'Edit Wallet';
$this->params['breadcrumbs'][] = ['label' => 'Wallets', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="wallet-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
