<?php


/* @var $this yii\web\View */
/* @var $model app\models\Downline */
/* @var $referrer string */

$this->title = 'Tambah Downline';
$this->params['breadcrumbs'][] = ['label' => 'Downlines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="downline-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>