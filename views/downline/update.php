<?php

/* @var $this yii\web\View */
/* @var $model app\models\Downline */
/* @var $referrer string */

$this->title = 'Edit Downline';
$this->params['breadcrumbs'][] = ['label' => 'Downlines', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="downline-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
