<?php

use app\components\Mode;
use app\components\Session;
use app\models\FundTicket;
use app\models\Paket;
use app\models\RefMetodePembayaran;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
// use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\Deposit */
/* @var $mode \app\components\Mode */
/* @var $referrer string */

$this->title = "Formulir Deposit";

$this->params['breadcrumbs'][] = ['label' => 'Deposit', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => 'Deposit'
    ],
]) ?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-12',
            'wrapper' => 'col-12',
            'error' => '',
            'hint' => '',
            'field' => 'mb-3 row',
        ],
        'options' => ['style' => 'padding:unset'],
    ],
    'enableClientScript' => false,
]); ?>

<div class="row">
    <div class="col-6">
        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3"><?= $this->title ?></h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($model) ?>

                    <?= $form->field($model, 'id_member', [
                        'template' => '{input}',
                        'options' => [
                            'tag' => false,
                        ],
                    ])->hiddenInput()?>

                    <?php /* $form->field($model, 'id_paket')->widget(Select2::classname(), [
                        'data' => Paket::getList(),
                        'options' => ['placeholder' => 'Select a state ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); */ ?>
                    
                    <?= $form->field($model, 'id_paket')->dropDownList(Paket::getList(), [
                            'prompt' => 'Pilih Paket',
                            'class' => 'form-control',
                            'required' => 'required',
                            'id' => 'id_paket'
                        ])->label('Pilihan Paket') ?>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Harga Paket</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>
                                <input type="text" class="form-control" id="harga_paket" name="Deposit[harga_paket]" value="<?= number_format(@$model->harga_paket, 0, ",", ".") ?>" required>
                            </div>
                        </div>
                    </div>

                    <?php /* $form->field($model, 'harga_paket')->textInput([
                        'readonly' => 'readonly',
                        'required' => 'required',
                        'id' => 'harga_paket'
                    ])->label('Harga Paket') */ ?>

                    <?= $form->field($model, 'id_ref_metode_pembayaran')->dropDownList(
                            RefMetodePembayaran::getList(), [
                            'prompt' => 'Pilih Metode Bayar',
                            'class' => 'form-control',
                            'required' => 'required',
                            'id' => 'id_ref_metode_pembayaran'
                    ]) ?>

                    <?php /* $form->field($model, 'ref_metode_pembayaran_harga')->textInput([
                        'readonly' => 'readonly',
                        'required' => 'required',
                        'id' => 'biaya'
                    ])->label('Biaya') */ ?>

                    <div class="row field-biaya" style="padding:unset">
                        <label class="col-12" for="biaya">Biaya</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>
                                <input type="text" class="form-control" id="biaya" name="Deposit[biaya]" required>
                            </div>
                        </div>
                    </div>

                    <?php /* $form->field($model, 'total_bayar')->textInput([
                        'readonly' => 'readonly',
                        'required' => 'required',
                        'id' => 'total_bayar'
                    ]) */ ?>

                    <div class="row field-total_bayar" style="padding:unset">
                        <label class="col-12" for="total_bayar">Total Bayar</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>
                                <input type="text" class="form-control" id="total_bayar" name="Deposit[total_bayar]" required>
                            </div>
                        </div>
                    </div>

                    <?php /*
                    <?= $form->field($model, 'status')->textInput() ?>
                    
                    <?= $form->field($model, 'created_at')->textInput() ?>

                    <?= $form->field($model, 'updated_at')->textInput() ?>

                    <?= $form->field($model, 'deleted_at')->textInput() ?>
                    */ ?>

                </div>
                <?= Html::hiddenInput('referrer', $referrer) ?>
                <div class="container-fluid mt-4">
                    <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2" id="btn-submit-text">' . ucwords('Submit') .'</span>', ['class' => 'btn btn-primary mb-1 float-right', 'id' => 'btn-submit', 'disabled' => 'disabled']) ?>
                    
                    <?php $myTicket = FundTicket::getBalance(Session::getIdMember()) ?? 0; ?>
                    <input type="hidden" id="my-ticket" value="<?= $myTicket ?>" readonly>
                    <p href="javascript:void(0);" class="text-muted mt-2 float-left font-italic" disabled="disabled">
                        <i class="ti-ticket"></i>
                        <span class="ml-2" style="">Ticket anda <?= $myTicket ?></span>
                    </p>
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

<?php ActiveForm::end(); ?>


<?php 

$urlGetListHargaPaket = Url::to(['/paket/get-list-harga-paket']);
$urlGetListRefMetodePembayaran = Url::to(['/ref-metode-pembayaran/get-list-ref-metode-pembayaran']);
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();

$script = <<<JS

    $('#id_paket').on('change', function() {
        const value = $(this).val();
        
        $.ajax({
            type: "POST",
            url: "{$urlGetListHargaPaket}",
            data: {
                'id_paket': value,
                "{$csrfParam}": "{$csrfToken}"
            },
            success: function(response) {
                let price = null;
                $('#harga_paket').val(price);

                const data = response?.data;                

                if (response?.data?.price != null && response?.data?.price != '') {
                    price = data?.price.toLocaleString("id", { maximumFractionDigits: 2 });
                    $('#harga_paket').val(price);
                }

                isDistributorSelected();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $('#id_ref_metode_pembayaran').on('change', function() {
        const value = $(this).val();
        
        $.ajax({
            type: "POST",
            url: "{$urlGetListRefMetodePembayaran}",
            data: {
                'id_ref_metode_pembayaran': value,
                "{$csrfParam}": "{$csrfToken}"
            },
            success: function(response) {
                let price = null;
                const data = response?.data;

                $('#biaya').val(price);

                if (response?.data?.harga != null) {

                    price = data?.harga.toLocaleString("id", { maximumFractionDigits: 2 });
                    $('#biaya').val(price);
    
                }

                isDistributorSelected();              
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    function isDistributorSelected() {
        /** value distributor = 4 */
        const value = $('#id_paket').val();
        if (value == 4) {
            $('#btn-submit').attr('disabled', true);
            $('#btn-submit-text').text('Contact Admin');
            return false;
        } else {
            // $('#btn-submit').attr('disabled', false);
            $('#btn-submit-text').text('Submit');
            // calculateTotalBayar();
        }

        calculateTotalBayar();
    }

    function calculateTotalBayar() {
        let selectPacket = $('#id_paket').val();
        let selectPaymentRef = $('#id_ref_metode_pembayaran').val();
        if (selectPacket == '' || selectPaymentRef == '') {
            $('#total_bayar').val('');
            return toggleSubmit(false);
        }

        let totalBayar = 0;
        let hargaPaket = $('#harga_paket').val().replaceAll(".", "");

        let biaya = $('#biaya').val().replaceAll(".", "");
        totalBayar = parseFloat(hargaPaket) + parseFloat(biaya);

        if (isNaN(totalBayar)) {
            totalBayar = 0;
        }        
                
        const myTicket = $('#my-ticket').val();

        if (totalBayar > 0) {
            $('#total_bayar').val(totalBayar.toLocaleString("id", { maximumFractionDigits: 2 }))
            if (myTicket > 0) {
                toggleSubmit(true)
            } else {
                alert('Tiket tidak cukup');
            }
        } else {
            $('#total_bayar').val(null);
            toggleSubmit(false);
        }
    }

    function toggleSubmit(flag) {
        if (flag === true) {
            $('#btn-submit').attr('disabled', false)
        } else {
            $('#btn-submit').attr('disabled', true)
        }
    }

JS;

$this->registerJs($script);

?>