<?php

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Roi */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Detail Roi";
if ($mode !== Mode::READ) {
    $this->title = ucwords(Mode::getText($mode)) . " Roi";
}
$this->params['breadcrumbs'][] = ['label' => 'Roi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Roi"
    ],
]) ?>

<?= $this->render('_form', [
    'model' => $model,
    'referrer'=> @$referrer,
    'mode' => $mode
]) ?>