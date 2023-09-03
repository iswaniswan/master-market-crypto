<?php


/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $referrer string */

$this->title = 'Tambah Groups';
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>