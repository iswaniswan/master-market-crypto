<?php


/* @var $this yii\web\View */
/* @var $model app\models\FundPassive */
/* @var $referrer string */

$this->title = 'Tambah Fund Passive';
$this->params['breadcrumbs'][] = ['label' => 'Fund Passives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-passive-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>