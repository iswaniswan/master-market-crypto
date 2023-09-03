<?php

/* @var $this yii\web\View */
/* @var $model app\models\FundActive */
/* @var $referrer string */

$this->title = 'Edit Fund Active';
$this->params['breadcrumbs'][] = ['label' => 'Fund Actives', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="fund-active-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
