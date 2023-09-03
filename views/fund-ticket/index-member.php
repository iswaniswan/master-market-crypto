<?php

use app\assets\DataTableAsset;
use app\models\FundRef;
use app\models\FundTicket;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FundTicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

DataTableAsset::register($this);

$this->title = 'Member Tickets';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Tickets'
    ],
]) ?>

<?php $balance = FundTicket::getBalance($member->id); ?>

<div class="row mb-4">
    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="ti-ticket float-right m-0 h2 text-success"></i>
            <h6 class="text-muted text-uppercase mt-0">Balance</h6>
            <h3 class="my-3 card-poin"><?= FundTicket::getBalance($member->id) ?></h3>
            <?php 
            $lastUsed = '-';
            $lastCredit = FundTicket::lastCredit($member->id);
            if ($lastCredit != null) {
                $lastUsed = date('d M Y', strtotime($lastCredit->date_created));
            }
            ?>
            <span>Terakhir diperbarui <?= $lastUsed ?></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card-box tilebox-one">
            <i class="ti-ticket float-right m-0 h2 text-danger"></i>
            <h6 class="text-muted text-uppercase mt-0">Used</h6>
            <h3 class="my-3 card-poin"><?= FundTicket::sumDebet($member->id) ?></h3>
            <?php 
            $lastUsed = '-';
            $lastDebet = FundTicket::lastDebet($member->id);
            if ($lastDebet != null) {
                $lastUsed = date('d M Y', strtotime($lastDebet->date_created));
            }
            ?>
            <span>Terakhir digunakan <?= $lastUsed ?></span>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="container-fluid">
        <div class="dt-button-wrapper">
            <?php 
            $isDisabled = '';
            $url = Url::to(['/fund-ticket/send-distributor']);
            if ($balance <= 0) {
                $isDisabled = 'disabled';
                $url = 'javascript:void(0)';
            }            
            ?>
            <a class="btn btn-primary mb-1 <?= $isDisabled ?>" href="<?= $url ?>"><i class="ti-location-arrow mr-2"></i> Send Ticket</a>            
        </div>

        <div class="member-index card-box shadow mb-4">
            <div class="mb-4">
                <h4 class="header-title" style="">
                    Riwayat Ticket
                </h4>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Referensi</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $index = 0; ?>
                        <?php foreach ($dataProvider->models as $model) { ?>
                            <tr>
                                <td><?= $index +1 ?></td>
                                <td><?= date('d M Y', strtotime($model->date_created)) ?></td>
                                <?php 
                                $transaksi = 'Receive';
                                if ($model->debet > 0) {
                                    $transaksi = 'Deliver';
                                }
                                ?>
                                <td><?= $model->getBadgeTransaction() ?></td>
                                <td>
                                    <?php 
                                    $refName = @$model->memberRef->nama;
                                    if ($model->id_member_ref == FundTicket::DEPOSIT) {
                                        $refName = 'Deposit';
                                    }

                                    echo $refName;
                                    ?>                                    
                                </td>
                                <td><?= $model->credit + $model->debet ?></td>
                                <td></td>
                            </tr>
                        <?php $index++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
$script = <<<JS

    $(document).ready(function() {
        $('table').dataTable();
    })                         

JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>