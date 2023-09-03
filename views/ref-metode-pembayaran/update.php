<?php

/* @var $this yii\web\View */
/* @var $model app\models\RefMetodePembayaran */
/* @var $referrer string */

$this->title = 'Edit Ref Metode Pembayaran';
$this->params['breadcrumbs'][] = ['label' => 'Ref Metode Pembayarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ref-metode-pembayaran-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
