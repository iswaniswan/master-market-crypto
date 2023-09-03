<?php
/** @var yii\web\View $this */
use app\components\Session;
use yii\helpers\Url;

$username = Session::getUsername();


$this->title = 'Dashboard Member';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>



<div class="row mb-4">
    <div class="container-fluid">
        <div class="dt-button-wrapper">
        </div>

        <div class="col-sm-6">
            <div class="card card-body">
                <h4 class="card-title">Halo <?= $username ?>,</h4>
                <p class="card-text">Anda belum memiliki paket yang aktif.</p>
                <a href="<?= Url::to(['paket/index-member']) ?>" class="btn btn-primary">Lihat Paket</a>
            </div>
        </div>
    </div>
</div>