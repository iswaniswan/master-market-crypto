<?php

use app\components\Mode;
use app\models\Paket;
use app\models\RefMetodePembayaran;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
// use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\Deposit */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Riwayat Deposit";

$this->params['breadcrumbs'][] = ['label' => 'Deposit', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Deposit'
    ],
]) ?>

<div class="row mb-4">
    <?php $index = 0; ?>
    <?php foreach ($dataProvider->models as $model) { ?>
        <?php $show = ($index == 0) ? 'show' : ''; ?> 
        
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

                <div id="collapseOne<?= $index ?>" class="collapse <?= $show ?>" aria-labelledby="headingOne" data-parent="#accordion<?= $index ?>">
                    <div class="card-box tilebox-one">
                        <div class="row" style="display: flex;">
                            <h6 class="col-3 offset-md-1">Member</h6>
                            <h6 class="col-6 text-muted"><?= strtoupper(@$model->member->nama) ?></h6>
                        </div>
                        <div class="row" style="display: flex;">
                            <h6 class="col-3 offset-md-1">Produk</h6>
                            <h6 class="col-6 text-muted">Paket <?= strtoupper(@$model->paket->name) ?></h6>
                        </div>
                        <div class="row" style="display: flex;">
                            <h6 class="col-3 offset-md-1">Harga</h6>
                            <h6 class="col-6 text-muted">IDR. <?= number_format(@$model->paket->price, 0, ",", ".")  ?></h6>
                        </div>
                        <div class="row" style="display: flex;">
                            <h6 class="col-3 offset-md-1">Metode Pembayaran</h6>
                            <h6 class="col-6 text-muted"><?= @$model->refMetodePembayaran->nama ?></h6>
                        </div>  
                        <div class="row" style="display: flex;">
                            <h6 class="col-3 offset-md-1">Biaya</h6>
                            <h6 class="col-6 text-muted">IDR. <?= number_format(@$model->refMetodePembayaran->harga, 0, ",", ".") ?></h6>
                        </div>  
                        <div class="row" style="display: flex;">
                            <h6 class="col-3 offset-md-1">Total</h6>
                            <h6 class="col-6 text-muted">IDR. <?= number_format(@$model->total_bayar, 0, ",", ".") ?></h6>
                        </div>                
                        <div class="row">
                            <h6 class="col-3 offset-md-1">Waktu Transaksi</h6>
                            <h6 class="col-6 text-muted"><?= $model->created_at ?></h6>
                        </div>
                        <div class="row">
                            <h6 class="col-3 offset-md-1">Status Transaksi</h6>
                            <h6 class="col-6 text-muted"><?= $model->getBadgeStatusTransaksi() ?></h6>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    <?php $index++; } ?>
</div>