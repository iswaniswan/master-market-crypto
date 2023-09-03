<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $mode string|null */
/* @var $referrer string|null */


$this->title = "Detail Member";
if ($mode !== 'view') {
    $this->title = ucwords($mode) . " Member";
}

$this->params['breadcrumbs'][] = ['label' => 'Member', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Member'
    ],
]) ?>

<?= $this->render('_form', [
        'model' => $model,
        'referrer'=> @$referrer,
        'mode' => $mode
]) ?>
