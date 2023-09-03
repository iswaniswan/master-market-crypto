<?php

use app\components\Mode;
use app\models\FundActive;
use app\models\FundPassive;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Withdraw */
/* @var $referrer string */

$this->title = "Withdraw Passive";

$this->params['breadcrumbs'][] = ['label' => 'Withdraw', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => " Withdraw"
    ],
]) ?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-12',
            'wrapper' => 'col-12',
            'error' => '',
            'hint' => '',
            'field' => 'mb-3 row',
        ],
        'options' => ['style' => 'padding:unset']
    ],
    'enableClientScript' => false
]); ?>

<div class="row">
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Saldo Passive</h6>
            <?php $balanceActive = FundPassive::getBalance($model->id_member); ?>
            <h3 class="my-3 text-success"><?= "IDR. " . number_format($balanceActive, 0, ",", ".") ?></h3>
        </div>
    </div>
    <div class="col-6">
        <div class="card-box tilebox-one">
            <i class="ti-money float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Total Withdraw</h6>
            <?php $totalWithdraw = FundPassive::totalWithdraw($model->id_member); ?>
            <h3 class="my-3 text-danger"><?= "IDR. " . number_format($totalWithdraw, 0, ",", ".") ?></h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">        
        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 1rem;">
                    <h4 class="card-title mb-3"><?= $this->title ?></h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($model) ?>

                    <?php // $form->field($model, 'id_transaksi')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'tipe')->hiddenInput()->label(false) ?>

                    <?= $form->field($model, 'id_member')->hiddenInput()->label(false) ?>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Jumlah Penarikan</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>
                                <input type="text" class="form-control" id="amount" name="Withdraw[amount]" required>
                            </div>
                        </div>
                    </div>

                    <?php // $form->field($model, 'amount')->textInput() ?>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Biaya Penarikan</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>
                                <input type="text" class="form-control" id="fee" name="Withdraw[fee]" value="10.000" readonly required>
                            </div>
                        </div>
                    </div>

                    <?php // $form->field($model, 'fee')->textInput() ?>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Tunai</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>
                                <input type="text" class="form-control" id="nett" name="Withdraw[nett]" readonly required>
                            </div>
                        </div>
                    </div>

                </div>
                <?= Html::hiddenInput('referrer', $referrer) ?>
                
                <div class="container-fluid mt-4">
                    <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords('Withdraw') .'</span>', [
                        'class' => 'btn btn-primary mb-1',
                        'onclick' => 'return validateMinimalAmount();'
                    ]) ?>
                </div>
            </div>            
        </div>
    </div>
    <div class="col-6">
        <div class="card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3">Informasi Deposit</h4>
                </div>
                - Deposit dan Reinvest menggunakan Tiket<br>
                - Untuk menambah Tiket, silahkan hubungi Upline / Sponsor Anda<br>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    
</div>

<?php ActiveForm::end(); ?>

<?php 

$script = <<<JS

    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $('#amount').on('focus', function() {
        if ($(this).val() == '0') {
            $(this).val('');
        }
    })
    .on('blur', function() {
        if ($(this).val() == '') {
            $(this).val('0');
        }
    })
    .on('keyup', function(e) {
        let newValue = formatRupiah(e.target.value);
        $(this).val(newValue);

        hitungNett();
    });

    function hitungNett() {
        let amount = $('#amount').val().toString().replaceAll(".", "");
        let fee = 10000;

        let net = parseFloat(amount) - fee;

        if (isNaN(net)) {
            net = 0;
        }

        $('#nett').val(net.toLocaleString("id", { maximumFractionDigits: 2 }))
    }

    function validateMinimalAmount() {
        let amount = $('#amount').val().toString().replaceAll(".", "");
        if (parseFloat(amount) < 100000) {
            alert('Minimal Penarikan adalah IDR. 100.000');            
            return false;
        }
    }

JS;

$this->registerJs($script, View::POS_END);

?>