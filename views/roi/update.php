<?php

/* @var $this yii\web\View */
/* @var $model app\models\Roi */
/* @var $referrer string */

$this->title = 'Edit Roi';
$this->params['breadcrumbs'][] = ['label' => 'Rois', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="roi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
