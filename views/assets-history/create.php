<?php


/* @var $this yii\web\View */
/* @var $model app\models\AssetsHistory */
/* @var $referrer string */

$this->title = 'Tambah Assets History';
$this->params['breadcrumbs'][] = ['label' => 'Assets Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assets-history-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>