<?php

/* @var $this yii\web\View */
/* @var $model app\models\Paket */
/* @var $referrer string */

$this->title = 'Edit Paket';
$this->params['breadcrumbs'][] = ['label' => 'Pakets', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="paket-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
