<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\web\View;

\app\assets\UplonAsset::register($this);

?>


<div class="row">
    <div class="col-10 mx-auto">
        <div class="card">
            <div class="card-header bg-soft-purple">
                <h6>Top 20 Currency on Market</h6>
            </div>
            <div class="card-body">
                <?php /**@var \app\models\Assets $model  */ ?>
                <?php foreach ($dataProvider->models as $model) { ?>
                    <a href="javascript:void(0)" class="row mb-4 mx-2" style="border-bottom: 1px solid #ccc; padding: 0.5rem 0.75rem;
                            border-radius: 15px;
                            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, .125);">
                        <div class="col">
                            <?= $model->getDefaultThumbnail() ?>
                        </div>
                        <div class="col">
                            <h6><?= strtoupper($model->symbol) ?></h6>
                        </div>
                        <div class="col">
                            <h6><?= $model->name ?></h6>
                        </div>
                        <div class="col">
                            <h6><?= "$" . number_format($model->price_usd, 2, ".", ",") ?></h6>
                        </div>
                        <div class="col">
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
                        <div class="col">
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


