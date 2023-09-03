<?php

/* @var $this yii\web\View */
/* @var $model app\models\AssetsHistory */
/* @var $referrer string */

$this->title = 'Edit Assets History';
$this->params['breadcrumbs'][] = ['label' => 'Assets Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="assets-history-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
