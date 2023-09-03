<?php


/* @var $this yii\web\View */
/* @var $model app\models\Reward */
/* @var $referrer string */

$this->title = 'Tambah Reward';
$this->params['breadcrumbs'][] = ['label' => 'Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
