<?php use app\models\Wallet;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$form = ActiveForm::begin([
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
        'options' => ['style' => 'padding:unset'],
    ],
    'enableClientScript' => false,
]); ?>
<div class="member-form card-box">
    <div class="card-body row">
        <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
            <h4 class="card-title mb-3">Swap</h4>
        </div>

        <div class="container-fluid">
            <?= $form->errorSummary($model) ?>

            <?php /*
            <?= $form->field($model, 'id_asset_coincap')->textInput([
                'value' => @$model->assets->name
            ])->label('Asset') ?>

            <?= $form->field($model, 'harga')->textInput([
                'value' => "USDT " . number_format(@$model->harga, 2, ".", ",")
            ]) ?>
            */ ?>

            <div class="row field-wallet-balance" style="padding:unset">
                <label class="col-12" for="wallet-balance">Asset</label>
                <div class="col-12">
                    <div class="input-group mb-3 mr-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><?= @$model->assets->symbol ?></span>
                        </div>
                        <?php
                        $balance = Wallet::getBalance(\app\components\Session::getIdMember(), $model->id);
                        ?>
                        <input type="number" step="0.00000001" class="form-control"
                            id="market-jual-wallet-balance" name="MarketJual[balance]"
                            max="<?= $balance ?>" required
                            value="<?= floatval($balance) ?>">
                    </div>
                </div>
            </div>

            <div class="row field-wallet-balance" style="padding:unset">
                <label class="col-12" for="wallet-balance">Return</label>
                <div class="col-12">
                    <div class="input-group mb-3 mr-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">USDT</span>
                        </div>
                        <?php
                        $return = 0;
                        if (@$model->harga_jual > 0) {
                            $return = $balance * $model->harga_jual;
                        }
                        ?>
                        <input type="text" class="form-control"
                            id="market-jual-wallet-return" name="MarketJual[return]" readonly
                            value="<?= number_format($return, 5, ".", ",") ?>">
                    </div>
                </div>
            </div>


            <div class="row mt-4">
                <div class="container-fluid text-right">
                    <?= Html::submitButton('<i class="icon-shuffle"></i><span class="ml-2">' . ucwords('Swap') .'</span>', ['class' => 'btn btn-primary mb-1', 'id' => 'market-jual-btn-swap']) ?>
                </div>
            </div>
        </div>

        <?= Html::hiddenInput('referrer', $referrer) ?>
        <?= Html::hiddenInput('id_crypto', $model->id) ?>

    </div>
</div>
<?php ActiveForm::end(); ?>

<?php

$kurs = 0 ;

if (@$model->harga_jual) {
    $kurs = $model->harga_jual;
}

$script = <<<JS

    $(document).ready(function() {
        $('#market-jual-wallet-balance').on('keyup change', function() {
            const t = $(this);
            let value = t.val();
            
            let _return = 0;
            if ({$kurs} > 0) {
                _return = value * {$kurs};
            }
            
            $('#market-jual-wallet-return').val(_return.toLocaleString("EN", { maximumFractionDigits: 8 }));
        })
        
        function canSwapButton() {
            let _balance = parseFloat($('#market-jual-wallet-balance').val());
            let _return = parseFloat($('#market-jual-wallet-return').val())
            if (_balance == 0 || _return == 0) {
                $('#market-jual-btn-swap').prop('disabled', 'disabled');
            }
        }
        
        canSwapButton();
    })

JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>
