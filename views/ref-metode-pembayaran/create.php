<?php


/* @var $this yii\web\View */
/* @var $model app\models\RefMetodePembayaran */
/* @var $referrer string */

$this->title = 'Tambah Ref Metode Pembayaran';
$this->params['breadcrumbs'][] = ['label' => 'Ref Metode Pembayarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-metode-pembayaran-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
