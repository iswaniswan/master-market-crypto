<?php

use app\components\Mode;
use app\components\Session;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $referrer string */

$this->title = 'Info Paket';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => "Paket"
    ],
]) ?>

<div class="member-update">

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
    <div class="col-8">
        <div class="dt-button-wrapper">
            <?php $id_member = Session::getIdMember(); ?>
            <?= Html::a('<i class="icon-user mr-2"></i>Profile', ['member/update-profile', 'id' => $id_member], ['class' => 'btn btn-success mb-1']) ?>
            <?= Html::a('<i class="icon-badge mr-2"></i>Paket', ['member/update-paket', 'id' => $id_member], ['class' => 'btn btn-purple mb-1']) ?>
            <?= Html::a('<i class="icon-wallet mr-2"></i>Bank', ['member/update-bank', 'id' => $id_member], ['class' => 'btn btn-info mb-1']) ?>
            <?= Html::a('<i class="icon-lock-open mr-2"></i>Security', ['member/update-security', 'id' => $id_member], ['class' => 'btn btn-danger mb-1']) ?>            
        </div>

        <div class="member-form card-box">
            <div class="card-body row">
                <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                    <h4 class="card-title mb-3"><?= $this->title ?></h4>
                </div>

                <div class="container-fluid">
                    <?= $form->errorSummary($model) ?>

                    <?= $form->field($model, 'id_paket')->textInput([
                            'readonly' => 'readonly',
                            'value' => strtoupper(@$model->paket->name)
                    ]) ?>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Omzet</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                </div>
                                <input type="text" class="form-control" value="<?= number_format(@$model->paket->price, 0, ",", ".") ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <?= $form->field($model, 'date_active')->textInput([
                            'readonly' => 'readonly',
                            'value' => date('d M Y H:i', strtotime(@$model->date_active))
                    ])->label('Tanggal Aktif') ?>
                    
                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Kode Referral</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <input type="text" class="form-control" value="<?= @$model->referral_code ?>" id="referral_code" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="copyContent()">
                                        <i class="ti-layers ml-2 h6 text-white"></i>
                                    </button>                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row field-harga_paket" style="padding:unset">
                        <label class="col-12" for="harga_paket">Link Referral</label>
                        <div class="col-12">
                            <div class="input-group mb-3 mr-3">
                                <?php 
                                $params = '/site/register?referral='.$model->referral_code;
                                $linkReferral = Url::base('https') . $params;
                                ?>
                                <input type="text" class="form-control" value="<?= $linkReferral ?>" id="link_referral_code" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="copyLinkReferral()">
                                        <i class="ti-layers ml-2 h6 text-white"></i>
                                    </button>                                    
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
        <?= Html::a('<i class="ti-arrow-left"></i><span class="ml-2">Back</span>', ['/dashboard/index-member'], ['class' => 'btn btn-info mb-1']) ?>
        <?php if ($mode == 'view') { ?>
            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
        <?php } else { ?>
            <?php // Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords($mode) .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
        <?php } ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

</div>


<?php 

$script = <<<JS


const copyContent = async () => {
    let text = $('#referral_code').val();

    try {
        await navigator.clipboard.writeText(text);
        alert('Content copied to clipboard');
        console.log('Content copied to clipboard', text);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}


const copyLinkReferral = async () => {
    let text = $('#link_referral_code').val();

    try {
        await navigator.clipboard.writeText(text);
        alert('Content copied to clipboard');
        console.log('Content copied to clipboard', text);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}

JS;

$this->registerJs($script, View::POS_END);

?>