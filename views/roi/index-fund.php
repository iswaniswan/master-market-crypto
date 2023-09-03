<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Riwayat ROI';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
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
                        <h6 class="text-muted text-uppercase mt-0"><?= $model->id_trx ?></h6>
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
                        <h6 class="col-6 text-muted"><?= strtoupper(@$model->member->nama) ?></h6>
                    </div>
                    <div class="row" style="display: flex;">
                        <h6 class="col-3 offset-md-1">Produk</h6>
                        <h6 class="col-6 text-muted">Return of Investment</h6>
                    </div>
                    <div class="row" style="display: flex;">
                        <h6 class="col-3 offset-md-1">Total</h6>
                        <h6 class="col-6 text-muted">IDR. <?= number_format(@$model->credit, 0, ",", ".") ?></h6>
                    </div>
                    <div class="row">
                        <h6 class="col-3 offset-md-1">Waktu Transaksi</h6>
                        <h6 class="col-6 text-muted"><?= date('d M Y H:i:s', strtotime($model->date_created)) ?></h6>
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