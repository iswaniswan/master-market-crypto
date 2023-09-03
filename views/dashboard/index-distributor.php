<?php
/** @var yii\web\View $this */
use app\components\Session;
use app\models\Deposit;
use app\models\FundActive;
use app\models\FundPassive;
use app\models\FundTicket;
use app\models\Groups;
use app\models\Member;
use app\models\Paket;
use app\models\Roi;
use app\models\Withdraw;
use app\widgets\UplonAlert;
use yii\helpers\Url;

$username = Session::getUsername();


$this->title = 'Dashboard Distributor';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>


<?php if (!Session::isDistributor()) { return; } ?>

<?php if (!Session::isMemberActive()) {
    echo $this->render('index-distributor-non-aktif', ['username' => $username]);
    return;
} ?>

<?php 
$member = Member::findOne(['id' => Session::getIdMember()]);
$group = $member->getGroupAsAdmin();

/** roi */
$currentRoi = Roi::getCurrentRoi();

if ($member->isDistributor() and $member->needToReinvest()) {

    echo UplonAlert::widget([
        'type' => UplonAlert::DANGER,
        'message' => 'Progress Roi mencapai 100%, Silakan Reinvest!',
        'actionUrl' => Url::to(['/dashboard'])
    ]);

}

?>

<?php /*
<div class="row mb-4">
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Saldo Active</h6>
            <?php $balanceActive = FundActive::getBalance($member->id); ?>
            <h3 class="my-3 text-success"><?= "IDR. " . number_format($balanceActive, 0, ",", ".") ?></h3>
        </div>
    </div>
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Saldo Passive</h6>
            <?php $totalWithdraw = FundPassive::getBalance($member->id); ?>
            <h3 class="my-3 text-danger"><?= "IDR. " . number_format($totalWithdraw, 0, ",", ".") ?></h3>
        </div>
    </div>
</div>
*/?>


<div class="row mb-4">
    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <h6 class="text-muted text-uppercase mt-0">Member Area</h6>
            <div class="row mt-4 mb-2" style="margin-left: -24px;">
                <a href="<?= Url::to(['member/update-profile', 'id' => $member->id]) ?>" class="col text-center">
                    <i class="icon-user m-2 h2 text-success"></i>
                    <span class="text-muted" style="display: block;">Profil</span>
                </a>
                <a href="<?= Url::to(['member/update-paket', 'id' => $member->id]) ?>" class="col text-center">
                    <i class="icon-badge m-2 h2 text-purple"></i>
                    <span class="text-muted" style="display: block;">Paket</span>
                </a>
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
    <div class="col-sm-3">
        <div class="card-box tilebox-one">
            <i class="ti-ticket float-right m-0 h2 text-success"></i>
            <h6 class="text-muted text-uppercase mt-0"><?= $member->paket->name ?></h6>
            <h3 class="my-3 card-poin"><?= FundTicket::getBalance($member->id) ?></h3>
            <?php $lastCredit = FundTicket::lastCredit($member->id) ?>
            <span>Terakhir diperbarui <?= date('d M Y', strtotime($lastCredit->date_created)) ?></span>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card-box tilebox-one">
            <i class="ti-reload float-right m-0 h2 text-success"></i>
            <h6 class="text-muted text-uppercase mt-0">RoI</h6>
            <h3 class="my-3 card-poin"><?= $currentRoi->roi ?> %</h3>
            <?php 
            $lastReceiveRoi = '-';
            $lastRoi = FundPassive::getLastReceiveRoi($member->id);
            if ($lastRoi != null) {
                $lastReceiveRoi = date('d M Y', strtotime($lastRoi->date_created));
            }
            ?>
            <span>Terakhir diperbarui <?= $lastReceiveRoi ?></span>
        </div>
    </div>
    <?php /*
    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="icon-tag float-right m-0 h2 text-success"></i>
            <h6 class="text-muted text-uppercase mt-0"><?= $member->paket->name ?></h6>
            <h3 class="my-3 card-poin"><?= $member->paket->poin ?></h3>
            <span>Aktif Sejak <?= date('d M Y', strtotime($member->date_active)) ?></span>
        </div>
    </div>
    */ ?>
</div>

<div class="row mb-4">
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Total Deposit Member</h6>
            <?php $totalDeposit = Deposit::getBalanceDistributor($member); ?>
            <h3 class="my-3 text-success"><?= "IDR. " . number_format($totalDeposit, 0, ",", ".") ?></h3>
        </div>
    </div>
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Total Withdraw Member</h6>
            <?php $totalWithdraw = Withdraw::getBalanceDistributor($member); ?>
            <h3 class="my-3 text-danger"><?= "IDR. " . number_format($totalWithdraw, 0, ",", ".") ?></h3>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Bonus Active</h6>
            <?php $balanceActive = FundActive::getBalance($member->id); ?>
            <h3 class="my-3 text-success"><?= "IDR. " . number_format($balanceActive, 0, ",", ".") ?></h3>
        </div>
    </div>
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Bonus Passive</h6>
            <?php $balancePassive = FundPassive::getBalance($member->id); ?>
            <h3 class="my-3 text-info"><?= "IDR. " . number_format($balancePassive, 0, ",", ".") ?></h3>
        </div>
    </div>
</div>


<div class="row mb-4">
    <?php foreach (Paket::find()->all() as $paket) { ?>
        <?php if ($paket->id == Paket::DISTRIBUTOR) { continue; } ?>
        <div class="col-md-3 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-people float-right m-0 h2 text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0"><?= $paket->name ?></h6>
                <h3 class="my-3" data-plugin="counterup">
                    <?= Member::getAllMemberCount([
                        'id_paket' => $paket->id,
                        'id_group' => $group->id_group
                    ]) ?>
                </h3>
                <div class="text-right" style="margin-top: -24px;">
                    <a href="<?= Url::to(['/member/index', 'id_paket' => $paket->id]) ?>" class="btn btn-primary">Detail</a>
                </div>
            </div>
        </div>
    <?php } ?>

</div>
