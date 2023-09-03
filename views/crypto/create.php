<?php


/* @var $this yii\web\View */
/* @var $model app\models\Crypto */
/* @var $referrer string */

$this->title = 'Tambah Crypto';
$this->params['breadcrumbs'][] = ['label' => 'Cryptos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crypto-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>