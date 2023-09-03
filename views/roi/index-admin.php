<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Mode;
use app\models\Paket;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'ROI';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
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
        'options' => ['style' => 'padding:unset'],
    ],
    'enableClientScript' => false
]); ?>

<div class="row">
    <div class="col-6">
        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3">Update ROI (Return Of Investment)</h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($model) ?>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">ROI Saat ini</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <input type="text" class="form-control" id="last_roi" name="last_roi" value="<?= $lastModel->roi ?>" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Estimasi ROI Distributor</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>

                                <?php $paketDistributor = Paket::findOne(['id' => Paket::DISTRIBUTOR]);
                                $est_roi_value = $paketDistributor->price * $lastModel->roi /100;
                                ?>
                                
                                <input type="text" class="form-control" id="est_roi_value" name="est_roi_value" value="<?= number_format($est_roi_value, 0, ",", ".") ?>" 
                                    data-value="<?= $paketDistributor->price ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row field-roi-roi" style="padding:unset">
                        <label class="col-12" for="roi-roi">ROI</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <input type="number" id="roi-roi" class="form-control" name="Roi[roi]" step="0.1" placeholder="0.0" onkeyup="calculateRoiValue()" onchange="calculateRoiValue()" required="required">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon1">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?= Html::hiddenInput('referrer', $referrer) ?>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="container-fluid">
        <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['index'], ['class' => 'btn btn-info mb-1']) ?>
        <?php if ($mode == Mode::READ) { ?>
            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
        <?php } else { ?>
            <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords('update') .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
        <?php } ?>
    </div>
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

    function calculateRoiValue() {
        let el = $('#est_roi_value');
        let elDataValue = el.attr('data-value');

        let roi = $('#roi-roi').val();
        let roiValue = elDataValue * roi / 100;

        el.val(formatRupiah(roiValue.toString()));
    }

JS;

$this->registerJs($script, View::POS_END);

?>