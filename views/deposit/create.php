<?php


/* @var $this yii\web\View */
/* @var $model app\models\Deposit */
/* @var $referrer string */

$this->title = 'Tambah Deposit';
$this->params['breadcrumbs'][] = ['label' => 'Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

createxxxxx