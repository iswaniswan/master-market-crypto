<?php


/* @var $this yii\web\View */
/* @var $model app\models\FundActive */
/* @var $referrer string */

$this->title = 'Tambah Fund Active';
$this->params['breadcrumbs'][] = ['label' => 'Fund Actives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-active-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>