<?php

use app\components\Mode;
use app\models\Deposit;
use app\models\FundRef;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $member app\models\Member */
/* @var $referrer string */
/* @var $id_fund_ref integer */

$this->title = 'Fund Statement';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';

$member = \app\models\Member::findOne(['id' => \app\components\Session::getIdMember()]);

?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>

<?php if ($id_fund_ref == FundRef::LEVEL or $id_fund_ref == FundRef::ROI or $id_fund_ref == FundRef::CASHBACK) {
    echo $this->render('_fund', [
        'model' => $model,
        'member' => $member
    ]);
} ?>

<?php if ($id_fund_ref == FundRef::LEVEL) {
    echo $this->render('_fund', [
        'model' => $model,
        'member' => $member
    ]);
} ?>
