<?php
/** @var yii\web\View $this */

use app\components\Session;
use app\models\FundActive;
use app\models\FundPassive;
use app\models\FundTicket;
use yii\helpers\Url;

$this->title = 'Dashboard Member';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>


<div class="row mb-4">
    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <h6 class="text-muted text-uppercase mt-0">Member Area</h6>
            <div class="row mt-4 mb-2" style="margin-left: -24px;">
                <a href="<?= Url::to(['member/update-profile', 'id' => $member->id]) ?>" class="col text-center">
                    <i class="icon-user m-2 h2 text-success"></i>
                    <span class="text-muted" style="display: block;">Profil</span>
                </a>
                <?php /*
                <a href="<?= Url::to(['member/update-paket', 'id' => $member->id]) ?>" class="col text-center">
                    <i class="icon-badge m-2 h2 text-purple"></i>
                    <span class="text-muted" style="display: block;">Paket</span>
                </a>
                */ ?>
                <a href="<?= Url::to(['member/update-bank', 'id' => $member->id]) ?>" class="col text-center">
                    <i class="icon-wallet m-2 h2 text-primary"></i>
                    <span class="text-muted" style="display: block;">Bank</span>
                </a>
                <a href="<?= Url::to(['member/update-security', 'id' => $member->id]) ?>" class="col text-center">
                    <i class="icon-lock-open m-2 h2 text-danger"></i>
                    <span class="text-muted" style="display: block;">Security</span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="icon-wallet float-right m-0 h2 text-success"></i>
            <h6 class="text-muted text-uppercase mt-0">Balance</h6>
            <h3 class="my-3 card-balance text-success" data-plugin="counterup"><?= (float) \app\models\Wallet::getBalance($member->id, \app\models\Wallet::USDT) ?></h3>
            <?php
            $lastCredit = FundTicket::lastCredit($member->id);
            if ($lastCredit != null) {
                $lastCredit = date('d M Y', strtotime(@$lastCredit->date_created));
            } else {
                $lastCredit = '-';
            }
            ?>
            <span>Terakhir diperbarui <?= $lastCredit ?></span>
            <div class="text-right" style="margin-top: -24px;">
                <a href="<?= Url::to(['/wallet/index']) ?>" class="btn btn-primary">Detail</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-soft-purple">
                <h6>Top 5 Currency on Market</h6>
            </div>
            <div class="card-body">
                <?php /**@var \app\models\Assets $model  */ ?>
                <?php foreach ($dataProvider->models as $model) { ?>
                    <a href="<?= Url::to(['/crypto/swap', 'id' => @$model->crypto->id]) ?>" class="row mb-4 mx-2" style="border-bottom: 1px solid #ccc; padding: 0.5rem 0.75rem;
                            border-radius: 15px;
                            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, .125);">
                        <div class="col-1">
                            <?= $model->getDefaultThumbnail() ?>
                        </div>
                        <div class="col-2">
                            <h6><?= strtoupper($model->symbol) ?></h6>
                        </div>
                        <div class="col-3">
                            <h6><?= $model->name ?></h6>
                        </div>
                        <div class="col-2">
                            <h6><?= "$" . number_format($model->price_usd, 2, ".", ",") ?></h6>
                        </div>
                        <div class="col-2">
                            <?php
                            $changePercent = $model->change_percent_24_hr;
                            $percent = number_format($changePercent, 2, ".", ",");

                            $icon = 'icon-arrow-up-circle text-success';
                            if ($percent < 0) {
                                $icon = 'icon-arrow-down-circle text-danger';
                            }

                            ?>
                            <h6><i class="<?= $icon ?> mr-2"></i><?= $percent ?>%</h6>
                        </div>
                        <div class="col-2">
                            <?php
                            $usdtPrice = "-";
                            if (@$model->crypto->harga > 0) {
                                $usdtPrice = "USDT " . number_format(@$model->crypto->harga, 2, ".", ",");
                            }
                            ?>
                            <h6><?= $usdtPrice ?></h6>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php

$style = <<<CSS
    .card-balance::before {
        content: "USDT ";
    }
CSS;

$this->registerCss($style);

?>