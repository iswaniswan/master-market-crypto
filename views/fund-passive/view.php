<?php

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FundPassive */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Detail Fund Passive";
if ($mode !== Mode::READ) {
    $this->title = ucwords(Mode::getText($mode)) . " Fund Passive";
}
$this->params['breadcrumbs'][] = ['label' => 'Fund Passive', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Fund Passive"
    ],
]) ?>

<?= $this->render('_form', [
    'model' => $model,
    'referrer'=> @$referrer,
    'mode' => $mode
]) ?>