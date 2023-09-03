<?php

use app\components\Mode;
use app\models\Deposit;
use app\models\FundRef;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $member app\models\Member */
/* @var $model \app\models\FundActive|\app\models\FundPassive */
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

<div class="row mb-4">
    <?php $index = 1; ?>
    <div class="col-12" id="accordion<?= $index ?>">
        <div class="tilebox-one">
            <div class="card-header" id="headingOne<?= $index ?>">
                <div class="row">
                    <div class="col-1">
                        <i class="icon-tag float-left mt-1 h2 text-muted"></i>
                    </div>
                    <div class="col-md-3 col-sm-10">
                        <h6 style="margin-top: unset;">Nomor Transaksi. </h6>
                        <h6 class="text-muted text-uppercase mt-0"><?= $model->id_transaksi ?></h6>
                    </div>
                    <button class="btn btn-link float-right" data-toggle="collapse" data-target="#collapseOne<?= $index ?>" aria-expanded="true" aria-controls="collapseOne<?= $index ?>">
                        <i class="ti-angle-down"></i>
                    </button>
                </div>
                </h5>
            </div>

            <div id="collapseOne<?= $index ?>" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion<?= $index ?>">
                <div class="card-box tilebox-one">
                    <div class="row" style="display: flex;">
                        <h6 class="col-3 offset-md-1">Member</h6>
                        <h6 class="col-6 text-muted"><?= strtoupper(@$member->nama) ?></h6>
                    </div>
                    <div class="row" style="display: flex;">
                        <h6 class="col-3 offset-md-1">Produk</h6>
                        <h6 class="col-6 text-muted">Withdraw</h6>
                    </div>
                    <div class="row" style="display: flex;">
                        <h6 class="col-3 offset-md-1">Amount</h6>
                        <h6 class="col-6 text-muted">IDR. <?= number_format(@$model->amount, 0, ",", ".") ?></h6>
                    </div>
                    <div class="row" style="display: flex;">
                        <h6 class="col-3 offset-md-1">Fee</h6>
                        <h6 class="col-6 text-muted">IDR. <?= number_format(@$model->fee, 0, ",", ".") ?></h6>
                    </div>
                    <div class="row" style="display: flex;">
                        <h6 class="col-3 offset-md-1">Nett</h6>
                        <h6 class="col-6 text-muted">IDR. <?= number_format(@$model->nett, 0, ",", ".") ?></h6>
                    </div>
                    <div class="row">
                        <h6 class="col-3 offset-md-1">Waktu Transaksi</h6>
                        <h6 class="col-6 text-muted"><?= date('d M Y H:i:s', strtotime($model->created_at)) ?></h6>
                    </div>
                    <div class="row">
                        <h6 class="col-3 offset-md-1">Status Transaksi</h6>
                        <h6 class="col-6 text-muted">
                            <span class="badge badge-pill badge-success" style="padding: 4px 16px;">SUCCESS</span>
                        </h6>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>