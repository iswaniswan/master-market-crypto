<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card-box tilebox-one">
            <i class="icon-tag float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0"><?= strtoupper($model->name) ?></h6>
            <h3 class="my-3 card-poin"><?= $model->poin ?></h3>
            <div class="row" style="display: flex;">
                <div class="container" style="position: relative">
                    <span class="text-muted" style="vertical-align: sub; font-size: larger;">
                        IDR. <?= number_format($model->price, 0, ",", ".") ?>
                    </span>                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-box tilebox-one">
            <i class="icon-direction float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Syarat & Ketentuan</h6>

            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item no-border no-pl">item 1</li>
                <li class="list-group-item no-border no-pl">item 2</li>
            </ul>
            
            <div class="mb-2">
                <input id="checkbox_agree" type="checkbox">
                <label for="checkbox_agree" class="text-muted">
                    Saya mengerti.
                </label>                
            </div>
            <a href="<?= Url::to(['paket/purchase', 'id_paket' => $model->id]) ?>"
                id="action-purchase"
                class="btn btn-primary waves-effect waves-light disabled" disabled>Beli Paket
            </a>
        </div>
    </div>
</div>
