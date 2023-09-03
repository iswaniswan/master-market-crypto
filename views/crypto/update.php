<?php

/* @var $this yii\web\View */
/* @var $model app\models\Crypto */
/* @var $referrer string */

$this->title = 'Edit Crypto';
$this->params['breadcrumbs'][] = ['label' => 'Cryptos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="crypto-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
