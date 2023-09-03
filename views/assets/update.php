<?php

/* @var $this yii\web\View */
/* @var $model app\models\Assets */
/* @var $referrer string */

$this->title = 'Edit Assets';
$this->params['breadcrumbs'][] = ['label' => 'Assets', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="assets-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
