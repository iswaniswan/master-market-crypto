<?php

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Wallet */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Detail Wallet";
if ($mode !== Mode::READ) {
    $this->title = ucwords(Mode::getText($mode)) . " Wallet";
}
$this->params['breadcrumbs'][] = ['label' => 'Wallet', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Wallet"
    ],
]) ?>

<?= $this->render('_form', [
    'model' => $model,
    'referrer'=> @$referrer,
    'mode' => $mode
]) ?>