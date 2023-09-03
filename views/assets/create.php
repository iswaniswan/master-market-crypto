<?php


/* @var $this yii\web\View */
/* @var $model app\models\Assets */
/* @var $referrer string */

$this->title = 'Tambah Assets';
$this->params['breadcrumbs'][] = ['label' => 'Assets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assets-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>