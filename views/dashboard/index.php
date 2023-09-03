<?php
/** @var yii\web\View $this */
use app\components\Session;
use app\models\Deposit;
use app\models\Member;
use app\models\Paket;
use app\models\Roi;
use app\models\User;
use app\models\Withdraw;
use yii\helpers\Url;

$username = Session::getUsername();


$this->title = 'Dashboard Admin';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>

<?php 
$member = Member::findOne(['id' => Session::getIdMember()]);
$group = $member->getGroupAsAdmin();

/** roi */
$currentRoi = Roi::getCurrentRoi();

?>

<div class="row mb-4">
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="icon-drop float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Active Currency</h6>
            <h3 class="my-3" data-plugin="counterup">
                <?= \app\models\Crypto::find()->where(['status' => 1])->count() ?>
            </h3>
            <div class="text-right" style="margin-top: -24px;">
                <a href="<?= Url::to(['/crypto/index']) ?>" class="btn btn-primary">Detail</a>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="icon-people float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Active User</h6>
            <h3 class="my-3" data-plugin="counterup">
                <?= Member::find()->count() ?>
            </h3>
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