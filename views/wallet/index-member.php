<?php

use app\models\FundTicket;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WalletSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Member Wallet';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Wallet'
    ],
]) ?>

<div class="row mb-4">
        <?php /*
        <div class="dt-button-wrapper">
            <?= Html::a('<i class="ti-plus mr-2"></i> Add', ['create'], ['class' => 'btn btn-primary mb-1']) ?>
            <?= Html::a('<i class="ti-printer mr-2"></i> Print', ['#'], ['class' => 'btn btn-info mb-1', 'onclick' => 'dtPrint()' ]) ?>
            <div class="btn-group mr-1">
                <?= Html::a('<i class="ti-download mr-2"></i> Export', ['#'], [
                    'class' => 'btn btn-success mb-1 dropdown-toggle',
                    'data-toggle' => 'dropdown'
                ]) ?>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <?= Html::a('Excel', ['#'], ['class' => 'dropdown-item', 'onclick' => 'dtExportExcel()']) ?>
                    <?= Html::a('Pdf', ['#'], ['class' => 'dropdown-item', 'onclick' => 'dtExportPdf()']) ?>
                </div>
            </div>
        </div>

        <?php $hasWallet = count($dataProvider->models) >= 1; ?>
        <?php if (!$hasWallet) { ?>
            <div class="row mb-4">
                <div class="col-6">
                    <div class="card-box tilebox-one">
                        <i class="icon-plus float-right m-0 h2 text-muted"></i>
                        <h6 class="text-muted text-uppercase mt-0">Add Wallet</h6>
                    </div>
                </div>
            </div>
        <?php } ?>
        */ ?>

        <?php foreach ($dataProvider->models as $model) { ?>
            <div class="col-sm-6">
                <div class="card-box tilebox-one">
                    <?php $crypto = \app\models\Crypto::getCrypto(@$model['id_crypto']) ?>
                    <h3 class="float-right m-0 h2 text-success"><?= @$crypto->assets->symbol ?></h3>
                    <h6 class="text-muted text-uppercase mt-0">Balance</h6>
                    <h3 class="my-3 card-balance text-success" data-plugin="counterup"><?= (float) $model['balance'] ?></h3>
                    <span>Terakhir diperbarui <?= date('d M Y H:i', strtotime($model['date_updated'])) ?></span>
                    <div class="text-right" style="margin-top: -24px;">
                        <a href="<?= Url::to(['/wallet/index-history', 'id_crypto' => $model['id_crypto']]) ?>" class="btn btn-primary">Detail</a>
                    </div>
                </div>
            </div>
        <?php } ?>
</div>
