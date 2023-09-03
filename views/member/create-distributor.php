<?php

use app\assets\Select2Asset;
use app\models\Downline;
use app\models\Paket;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
/* @var $referrer string */

$this->title = "Register Distributor";
$this->params['breadcrumbs'][] = ['label' => 'Member', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>


<div class="member-create">

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
        <div class="col-md-6 col-sm-12">
            <div class="member-form card-box" style="border: 1px solid #1bb99a;">
                <div class="card-body row">
                    <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                        <h4 class="card-title mb-3">Membership</h4>
                    </div>

                    <div class="container-fluid mb-4">
                        <?= $form->errorSummary($model) ?>

                        <?= $form->field($model, 'id_member_sponsor')->textInput([
                            'readonly' => 'readonly',
                            'value' => strtoupper($memberAdmin->nama)
                        ])->label('Sponsor') ?>

                        <?= $form->field($model, 'id_member_upline')->textInput([
                            'required' => 'required',
                            'readonly' => 'readonly',
                            'value' => strtoupper(@$memberAdmin->nama)
                        ])->label('Upline') ?>

                        <?php /*
                        <div class="row field-posisi mb-3" style="padding:unset">
                            <label class="col-12" for="">Posisi</label>
                            <div class="col-12">
                                <input type="hidden" name="Member[posisi]" id="posisi" value="<?= @$posisi ?>" required>
                                <input type="text" class="form-control" value="<?= Downline::getListPosisi()[@$posisi] ?>" readonly>
                            </div>
                        </div>
                        */?>

                        <?= $form->field($model, 'nama')->textInput([
                                'maxlength' => true,
                                'required' => 'required',                                
                        ])->label('Nama Lengkap') ?>

                        <?= $form->field($model, 'email')->textInput([
                                'type' => 'email',
                                'maxlength' => true,
                                'required' => 'required',
                                'id' => 'email'                             
                        ])->label('Email') ?>

                        <div class="row field-harga_paket" style="padding:unset">
                            <label class="col-12" for="username">Username</label>
                            <div class="col-12">
                                <div class="input-group mb-3 mr-3">
                                    <input type="text" class="form-control" name="Member[username]" id="username" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success btn-sm" onclick="generateUsername()" title="Generate">
                                            <i class="ti-shield px-2 h6 text-white"></i>
                                        </button>                                    
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row field-harga_paket" style="padding:unset">
                            <label class="col-12" for="password">Password</label>
                            <div class="col-12">
                                <div class="input-group mb-3 mr-3">
                                    <input type="text" class="form-control" name="Member[password]" id="password" minlength='8' required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success btn-sm" onclick="generatePassword()" title="Generate">
                                            <i class="ti-shield px-2 h6 text-white"></i>
                                        </button>                                    
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row field-harga_paket" style="padding:unset">
                            <label class="col-12" for="pin">PIN</label>
                            <div class="col-12">
                                <div class="input-group mb-3 mr-3">
                                    <input type="text" class="form-control" name="Member[pin]" id="pin" minlength='5' maxlength='6' required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success btn-sm" onclick="generatePin()" title="Generate">
                                            <i class="ti-shield px-2 h6 text-white"></i>
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

        <div class="col-md-6 col-sm-12">
        <div class="member-form card-box" style="border: 1px solid #1bb99a;">
                <div class="card-body row">
                    <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                        <h4 class="card-title mb-3">Area</h4>
                    </div>
                    <div class="container-fluid">

                    <div class="mb-3 row field-member-id_reff_kotakab" style="padding:unset">
                        <label class="col-12" for="member-id_reff_kotakab">Kota/Kabupaten</label>
                        <div class="col-12">
                            <select id="member-id_reff_kotakab" class="form-control" name="Member[id_reff_kotakab]" required="required" style="text-transform:uppercase"></select>
                        <div class="valid-feedback "></div>
                        </div>
                    </div>
                    <div class="row field-member-id_reff_kotakab" style="padding:unset">
                        <div class="col-12">
                            <span class="text-muted font-italic" style="font-size: 11px;">
                                Jika nama daerah tidak muncul, hubungi Admin.   
                            </span>                            
                        </div>
                    </div>

                    </div>
                </div>
            </div>

            <div class="member-form card-box" style="border: 1px solid #1bb99a;">
                <div class="card-body row">
                    <div class="col-12" style="border-bottom: 1px solid #ccc; margin-bottom: 2rem;">
                        <h4 class="card-title mb-3">Payment</h4>
                    </div>

                    <div class="container-fluid mb-4">

                        <?= $form->field($model, 'bank')->textInput([
                            'required' => 'required',
                            'style' => 'text-transform:uppercase'
                        ])->label('Bank') ?>

                        <?= $form->field($model, 'rekening')->textInput([
                            'type' => 'text',
                            'required' => 'required',
                        ])->label('Rekening') ?>

                        <?= $form->field($model, 'rekening_an')->textInput([
                            'type' => 'text',
                            'required' => 'required',
                        ])->label('Atas Nama') ?>

                        <?= $form->field($model, 'phone')->textInput([
                            'type' => 'number',
                            'required' => 'required',
                        ])->label('Whatsapp') ?>

                        <div class="row field-harga_paket" style="padding:unset">
                            <label class="col-12" for="tos">Info</label>
                            <div class="col-12">
                                <div class="input-group mb-3 mr-3">
                                    <textarea type="text" class="form-control" readonly>Info</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="container-fluid text-right">
                        <?= Html::a('<i class="ti-reload"></i><span class="ml-2">Reset</span>', ['/member/create-member-downline'], ['class' => 'btn btn-info mb-1']) ?>
                        <?php if ($mode == 'view') { ?>
                            <?= Html::a('<i class="ti-pencil-alt"></i><span class="ml-2">Edit</span>', ['update', 'id' => $model->id], ['class' => 'btn btn-warning mb-1']) ?>
                        <?php } else { ?>
                            <?= Html::submitButton('<i class="ti-check"></i><span class="ml-2">' . ucwords($mode) .'</span>', ['class' => 'btn btn-primary mb-1']) ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php 
/** load library */
Select2Asset::register($this);

?>


<?php 
$urlGetListRefKotaKab = Url::to(['/member/get-list-ref-kota-kab']);

$urlGenerateUsername = Url::to(['/member/generate-username']);
$urlGeneratePassword = Url::to(['/member/generate-password']);
$urlGeneratePin = Url::to(['/member/generate-pin']);
$urlCheckUsernameExists = '';

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();

$script = <<<JS

    $('#email').on('keyup', function() {
        const value = $(this).val();

        if (value.search('@') >= 0) {
            generateUsername();
        }
    })

    function generateUsername() {
        $.ajax({
            type: "POST",
            url: "{$urlGenerateUsername}",
            data: {
                'email': $('#email').val(),
                "{$csrfParam}": "{$csrfToken}"
            },
            success: function(response) {
                console.log(response);
                const data = response?.data;
                $('#username').val(data?.username);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function generatePassword() {
        $.ajax({
            type: "POST",
            url: "{$urlGeneratePassword}",
            data: {
                "{$csrfParam}": "{$csrfToken}"
            },
            success: function(response) {
                console.log(response);
                const data = response?.data;
                $('#password').val(data?.password);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function generatePin() {
        $.ajax({
            type: "POST",
            url: "{$urlGeneratePin}",
            data: {
                "{$csrfParam}": "{$csrfToken}"
            },
            success: function(response) {
                console.log(response);
                const data = response?.data;
                $('#pin').val(data?.pin);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    $('#member-id_reff_kotakab').select2({
        width: "100%",
        allowClear: true,
        maximumSelectionLength: 2,
        ajax: {
            url: "{$urlGetListRefKotaKab}",
            dataType: "json",
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                };
                return query;
            },
            processResults: function (data) {
                console.log(data);
                return {
                    results: data.data,
                };
            },
            cache: false,
        }
    });


JS;

$this->registerJs($script, View::POS_END);

?>