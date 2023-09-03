<?php

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FundTicket */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Detail Fund Ticket";
if ($mode !== Mode::READ) {
    $this->title = ucwords(Mode::getText($mode)) . " Fund Ticket";
}
$this->params['breadcrumbs'][] = ['label' => 'Fund Ticket', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Fund Ticket"
    ],
]) ?>

<?= $this->render('_form', [
    'model' => $model,
    'referrer'=> @$referrer,
    'mode' => $mode
]) ?>