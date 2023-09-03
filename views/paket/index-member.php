<?php

use app\models\Paket;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Daftar Paket';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Paket'    ],
]) ?>

<div class="row mb-4">
    <?php foreach ($dataProvider->models as $model) { ?>
        <div class="col-md-6">
            <div class="card-box tilebox-one">
                <i class="icon-tag float-right m-0 h2 text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0"><?= strtoupper($model->name) ?></h6>
                <h3 class="my-3 card-poin" data-plugin="counterup"><?= $model->poin ?></h3>
                <div class="row" style="display: flex;">
                    <div class="container" style="position: relative">
                        <span class="text-muted" style="vertical-align: sub; font-size: larger;">
                            USDT. <?= number_format($model->price, 0, ",", ".") ?>
                        </span>
                        <?php 
                        $url = Url::to(['deposit/create-member', 'id_paket' => $model->id]);
                        $html = '<a href="'.$url.'" class="btn btn-primary btn-rounded waves-effect waves-light float-right">Detail</a>';

                        if ($model->id == Paket::DISTRIBUTOR) {
                            $html = '<a href="javascript:void(0)" class="btn btn-primary btn-rounded waves-effect waves-light float-right disabled">Contact Admin</a>';
                        }
                        
                        echo $html;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>


<?php 

$style = <<<CSS
    .card-poin::before {
        content: '+';
        margin-right: 5px;
    }
    .card-poin::after {
        content: 'PAKET';
        margin-left: 10px;
    }
CSS;

$this->registerCss($style);

?>