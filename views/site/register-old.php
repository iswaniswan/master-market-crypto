<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\web\View;

\app\assets\UplonAsset::register($this);

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row mb-4 justify-content-center">
    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
//                'template' => "{label}\n{input}\n{error}",
            'template' => "{label}\n{input}",
            'labelOptions' => ['class' => 'col-12 col-form-label', 'style' => 'font-weight: 400', 'icon' => '<i></i>'],
            'inputOptions' => ['class' => 'col-12 form-control'],
//                'errorOptions' => ['class' => 'col-12 invalid-feedback'],
            'horizontalCssClasses' => [
                'field' => 'mb-3',
            ]
        ],
    ]); ?>

    <!-- card 1 -->        
    <div class="col-md-6">
        <div class="card mb-0">
            <div class="card-header bg-indigo" style="height: 183px">
                <div class="text-center">
                    <div class="my-3">
                        <a href="#">
                            <img src="<?= Yii::getAlias('@web').'/images/LOGO.png' ?>" style="width:100%; max-height:100px; object-fit:scale-down">
                        </a>
                        <!-- <h3 class="text-center text-white">AUTOCAREPRO</h3> -->
                        <!-- <h4 class="text-center text-white"><?= strtoupper(@$area) ?></h4> -->
                    </div>
                    <!-- <h5 class="text-white text-uppercase py-3 font-16"><?= Html::encode($this->title) ?></h5> -->
                    <p class="text-white-50">Please fill out the following fields</p>
                </div>
            </div>
            <div class="card-body">
                <?php 
                $referralValue = null;
                $attribute = 'required';
                if (@$referral != null) {
                    $referralValue = $referral;
                    $attribute = 'readonly';
                }
                
                ?>
                <div class="mb-3 field-referral-code required">
                    <label class="col-12 col-form-label" style="font-weight: 400" icon="<i></i>" for="referral-code">Referral Code</label>
                    <input type="text" id="referral_code" maxlength="8" class="col-12 form-control" 
                        name="User[registered_referral_code]" 
                        value="<?= $referralValue ?>" aria-required="true" 
                        aria-invalid="false" <?= $attribute ?>>
                </div>     
                
                <?= $form->field($model, 'nama')->textInput([
                        'maxlength' => true,
                        'required' => 'required',                                
                ])->label('Nama Lengkap') ?>           

            </div> <!-- end card-body -->
        </div>
        <!-- end card -->
        <!-- end row -->
    </div>

    <!-- card 2 -->
    <div class="col-md-6">
        <div class="card mb-0">            
            <div class="card-body">

                <?= $form->field($model, 'email')->input('email', [
                        'required' => true,
                        'id' => 'email'
                ]) ?>

                <div class="mb-3 field-harga_paket" style="padding:unset">
                    <label class="col-12 col-form-label " for="username">Username</label>
                    <div class="input-group mb-3 mr-3">
                        <input type="text" class="col-12 form-control " name="User[username]" id="username" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-warning btn-sm" onclick="generateUsername()" title="Generate">
                                <i class="ti-shield px-2 h6 text-white"></i>
                            </button>                                    
                        </div>
                    </div>
                </div>

                <div class="mb-3 field-harga_paket" style="padding:unset">
                    <label class="col-12 col-form-label " for="password">Password</label>
                    <div class="input-group mb-3 mr-3">
                        <input type="text" class="col-12 form-control " name="User[password]" id="password" minlength='8' required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-warning btn-sm" onclick="generatePassword()" title="Generate">
                                <i class="ti-shield px-2 h6 text-white"></i>
                            </button>                                    
                        </div>
                    </div>
                </div>

                <?= $form->field($model, 'accept_terms')->checkbox([
                    'template' => "<div class=\"col-12 checkbox checkbox-purple ml-2 \">{input} {label}</div>\n<div class=\"col-12\">{error}</div>",
                    'required' => true,
                ])->label('I accept Terms and Conditions') ?>

                <div class="" style="padding-bottom: 0.5rem; margin-bottom: 7px">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-outline-purple', 'name' => 'login-button']) ?>
                </div>
            </div> <!-- end card-body -->
        </div>
        <!-- end card -->
        <!-- end row -->
    </div>


    <?php ActiveForm::end(); ?>
</div>

<div class="row mt-3">
    <div class="col-12 text-center">
        <p class="">Have an account? <a href="<?= \yii\helpers\Url::to(['/site/login']) ?>" class="text-purple ml-1"><b>Login</b></a></p>
    </div> <!-- end col -->
</div>

<?php 
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

JS;

$this->registerJs($script, View::POS_END);

$style = <<<CSS
    @media (min-width: 768px) {

        #register-form {
            display: flex;
            width: 60%;
        }
    
        div.col-md-6:nth-child(2) {
            padding-right: unset;
        }
        div.col-md-6:nth-child(2) > .card {
            max-width: 30rem;
            -webkit-box-shadow: -15px 0 35px 0 rgba(73,80,87,.15);
        }
    
        div.col-md-6:nth-child(3) {
            padding-left: unset;
        }
        div.col-md-6:nth-child(3) > .card {
            max-width: 30rem;
            -webkit-box-shadow: 15px 0 35px 0 rgba(73,80,87,.15);
        }
    }

CSS;

$this->registerCss($style);

?>



