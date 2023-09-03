<?php

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RewardClaimed */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Detail Reward Claimed";
if ($mode !== Mode::READ) {
    $this->title = ucwords(Mode::getText($mode)) . " Reward Claimed";
}
$this->params['breadcrumbs'][] = ['label' => 'Reward Claimed', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Reward Claimed"
    ],
]) ?>

<?= $this->render('_form', [
    'model' => $model,
    'referrer'=> @$referrer,
    'mode' => $mode
]) ?>