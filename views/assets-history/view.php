<?php

use app\components\Mode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AssetsHistory */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Detail Assets History";
if ($mode !== Mode::READ) {
    $this->title = ucwords(Mode::getText($mode)) . " Assets History";
}
$this->params['breadcrumbs'][] = ['label' => 'Assets History', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Assets History"
    ],
]) ?>

<?= $this->render('_form', [
    'model' => $model,
    'referrer'=> @$referrer,
    'mode' => $mode
]) ?>