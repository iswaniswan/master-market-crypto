<?php

use app\assets\DataTableAsset;
use app\components\Mode;
use app\models\Deposit;
use app\models\FundRef;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $referrer string */

$this->title = 'Fund Statement';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';

DataTableAsset::register($this);

?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>

<div class="member-update row">
    <div class="col-12">
        <div class="dt-button-wrapper">
            <a class="btn btn-info mb-1" href="javascript:void(0)" onclick="dtPrint()"><i class="ti-printer mr-2"></i> Print</a>            
            <div class="btn-group mr-1">
                <a class="btn btn-success mb-1 dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown">
                    <i class="ti-download mr-2"></i> Export</a>                
                    <div class="dropdown-menu" x-placement="bottom-start">
                <a class="dropdown-item" href="javascript:void(0)" onclick="dtExportExcel()">Excel</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="dtExportPdf()">Pdf</a>                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">            
        <div class="card-box">
            <div class="mb-4">
                <h4 class="header-title" style="">
                    <?= $this->title ?>
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
                        <?php foreach($result as $row) { ?>
                            <?php 
                            $row = (object)$row; 
                            $value = $row->credit + $row->debet;

                            $transaksi = 'Credit';
                            $linkTransaksi = Url::to([
                                    '/member/index-fund-statement-view',
                                    'id_fund_ref' => $row->id_fund_ref,
                                    'id_trx' => $row->id_trx
                                ]);
                            if ($row->debet > $row->credit) {
                                $fundRef = FundRef::findOne(['id' => $row->id_fund_ref]);
                                $transaksi = ucwords(@$fundRef->nama);
                            }

                            ?>
                            <tr>
                                <td><?= $index +1 ?></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($row->date_created)) ?></td>
                                <td><?= $transaksi ?></td>
                                <td>
                                    <a href="<?= $linkTransaksi ?>" class="text-info">
                                        <?= $row->id_trx ?>
                                    </a>
                                </td>
                                <td>IDR. <?= number_format($value, 0, ",", ".") ?></td>
                                <td>
                                    <?php $fundRef = FundRef::findOne(['id' => $row->id_fund_ref]); ?>
                                    <?= @$fundRef->keterangan ?>
                                </td>
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

    const dtPrint = () => {
        const dtBtn = $('.btn.buttons-print');
        dtBtn.trigger('click');
    }
    const dtExportPdf = () => {
        const dtBtn = $('.btn.buttons-pdf.buttons-html5');
        dtBtn.trigger('click');
    }
    const dtExportExcel = (e) => {
        const dtBtn = $('.btn.buttons-excel.buttons-html5');
        dtBtn.trigger('click');
    }

    $(document).ready(function() {
        $('table').dataTable();
    })                         

JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>