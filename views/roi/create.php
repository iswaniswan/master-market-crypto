<?php


/* @var $this yii\web\View */
/* @var $model app\models\Roi */
/* @var $referrer string */

$this->title = 'Tambah Roi';
$this->params['breadcrumbs'][] = ['label' => 'Rois', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>