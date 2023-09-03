<?php

use app\components\Mode;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $referrer string */

$this->title = 'Update Profile';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => "Member"
    ],
]) ?>

<div class="member-update row">    

    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="icon-user float-right m-0 h2 text-success"></i>
            <h6 class="text-muted text-uppercase mt-0">Profil</h6>
            <p>Informasi data diri dan alamat</p>
            <a href="" class="btn btn-primary">Lihat</a>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="icon-badge float-right m-0 h2 text-purple"></i>
            <h6 class="text-muted text-uppercase mt-0">Membership</h6>
            <p>Informasi paket dan bonus poin</p>
            <a href="" class="btn btn-primary">Lihat</a>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="icon-wallet float-right m-0 h2 text-primary"></i>
            <h6 class="text-muted text-uppercase mt-0">Bank</h6>
            <p>Informasi bank</p>
            <a href="" class="btn btn-primary">Lihat</a>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="icon-lock-open float-right m-0 h2 text-danger"></i>
            <h6 class="text-muted text-uppercase mt-0">Akun</h6>
            <p>Informasi login dan keamanan akun</p>
            <a href="" class="btn btn-primary">Lihat</a>
        </div>
    </div>
</div>
