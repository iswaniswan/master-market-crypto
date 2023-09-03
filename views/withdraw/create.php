<?php


/* @var $this yii\web\View */
/* @var $model app\models\Withdraw */
/* @var $referrer string */

$this->title = 'Tambah Withdraw';
$this->params['breadcrumbs'][] = ['label' => 'Withdraws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="withdraw-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
