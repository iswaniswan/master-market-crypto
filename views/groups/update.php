<?php

/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $referrer string */

$this->title = 'Edit Groups';
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="groups-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
