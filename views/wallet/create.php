<?php


/* @var $this yii\web\View */
/* @var $model app\models\Wallet */
/* @var $referrer string */

$this->title = 'Tambah Wallet';
$this->params['breadcrumbs'][] = ['label' => 'Wallets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallet-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>