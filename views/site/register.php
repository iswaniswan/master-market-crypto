<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\web\View;

\app\assets\RegisterAsset::register($this);
\app\assets\UplonAsset::register($this);

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center">
    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
//                'template' => "{label}\n{input}\n{error}",
            'template' => "{label}\n{input}",
            'labelOptions' => ['class' => 'col-12', 'style' => 'font-weight: 400', 'icon' => '<i></i>'],
            'inputOptions' => ['class' => 'col-12 form-control'],
//                'errorOptions' => ['class' => 'col-12 invalid-feedback'],
            'horizontalCssClasses' => [
                'field' => 'mb-3',
            ]
        ],
    ]); ?>

    <div class="card">
        <div class="card-body">

            <div class="col-12 p-4">
                <div class="row text-center">
                    <a href="#" class="col">
                        <img src="<?= Yii::getAlias('@web').'/images/LOGO.png' ?>" style="width:70%; max-height:70px; object-fit:scale-down">
                    </a>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <h4 class="header-title mb-5">Registrasi Member</h4>
                <div class="row">
                    <?php /*
                    <div class="col">
                        <a id="t-0" data-index="0" class="btn btn-primary btn-action btn-rounded mx-auto mb-2" href="#h-0"
                           style="border-radius: 100% !important; height: 48px; width: 48px; display: flex; position: relative">
                            <span class="icon-link" style="font-size: 1rem; position: absolute; top: 29%; left: 33%"></span>
                        </a>
                        <p class="text-center">Referral</p>
                    </div>
                    */ ?>
                    <div class="col">
                        <a id="t-1" data-index="1" class="btn btn-primary btn-action btn-rounded disabled mx-auto mb-2" href="#h-1"
                           style="border-radius: 100% !important; height: 48px; width: 48px; display: flex; position: relative">
                            <span class="icon-key" style="font-size: 1rem; position: absolute; top: 29%; left: 33%"></span>
                        </a>
                        <p class="text-center">Account</p>
                    </div>
                    <div class="col">
                        <a id="t-2" data-index="2" class="btn btn-primary btn-action btn-rounded disabled mx-auto mb-2" href="#h-2"
                           style="border-radius: 100% !important; height: 48px; width: 48px; display: flex; position: relative">
                            <span class="icon-user" style="font-size: 1rem; position: absolute; top: 29%; left: 33%"></span>
                        </a>
                        <p class="text-center">Profile</p>
                    </div>
                    <div class="col">
                        <a id="t-3" data-index="3" class="btn btn-primary btn-action btn-rounded disabled mx-auto mb-2" href="#h-3"
                           style="border-radius: 100% !important; height: 48px; width: 48px; display: flex; position: relative">
                            <span class="icon-wallet" style="font-size: 1rem; position: absolute; top: 29%; left: 33%"></span>
                        </a>
                        <p class="text-center">Wallet</p>
                    </div>
                </div>
            </div>
            <div id="example-basic">
                <?php /*
                <h3></h3>
                <section>
                    <div class="col-12">
                        <?php
                        $referralValue = null;
                        $attribute = 'required';
                        if (@$referral != null) {
                            $referralValue = $referral;
                            $attribute = 'readonly';
                        }

                        ?>
                        <div class="field-referral-code required">
                            <label class="col-12" style="padding-left: unset" for="referral-code">*Referral Code</label>


                            <div class="input-group">
                                <input type="text" id="referral_code" maxlength="8" class="col-12 form-control"
                                       name="User[registered_referral_code]"
                                       value="<?= $referralValue ?>" aria-required="true"
                                       aria-invalid="false" <?= $attribute ?>>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info btn-sm" onclick="actionStep()">
                                        <span class="icon-lock-open px-2 text-white"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                */ ?>

                <h3></h3>
                <section>
                    <div class="field-email required">
                        <label class="col-12" style="padding-left: unset" for="email">Email</label>
                        <input type="email" id="email" class="col-12 form-control" name="User[email]" required aria-required="true">
                    </div>

                    <div class="field-harga_paket" style="padding:unset">
                        <label class="col-12" style="padding-left: unset" for="username">Username</label>
                        <div class="input-group">
                            <input type="text" class="col-12 form-control " name="User[username]" id="username" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-warning btn-sm" onclick="generateUsername()" title="Generate">
                                    <i class="ti-shield px-2 text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="field-harga_paket" style="padding:unset">
                        <label class="col-12" style="padding-left: unset" for="password">Password</label>
                        <div class="input-group">
                            <input type="text" class="col-12 form-control " name="User[password]" id="password" minlength='8' required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-warning btn-sm" onclick="generatePassword()" title="Generate">
                                    <i class="ti-shield px-2 text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <h3></h3>
                <section>
                    <?= $form->field($model, 'nama')->textInput([
                        'maxlength' => true,
                        'required' => 'required',
                    ])->label('Nama Lengkap') ?>

                    <?= $form->field($model, 'phone')->textInput([
                        'maxlength' => true,
                        'required' => 'required',
                    ])->label('Telepon') ?>
                </section>

                <h3></h3>
                <section>
                    <?= $form->field($model, 'bank')->textInput([
                        'maxlength' => true,
                        'required' => 'required',
                    ])->label('Bank') ?>

                    <?= $form->field($model, 'rekening')->textInput([
                        'maxlength' => true,
                        'required' => 'required',
                    ])->label('No. Rekening') ?>

                    <?= $form->field($model, 'rekening_an')->textInput([
                        'maxlength' => true,
                        'required' => 'required',
                    ])->label('Atas Nama Rekening') ?>
                </section>
            </div>
            <?= Html::submitButton('', ['class' => 'd-none', 'id' => 'btn-register-submit']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    </div>

    <div class="row">
        <div class="col-12 text-center">
        <p class="">Have an account? <a href="<?= \yii\helpers\Url::to(['/site/login']) ?>" class="text-purple ml-1"><b>Login</b></a></p>
    </div>


        <?php
        $checked = ''; $flag = false;
        $cookie = Yii::$app->request->cookies->getValue('dark-mode');
        if ($cookie != null and $cookie == true) {
            $checked = 'checked';
            $flag = true;
        }
        ?>
    <div class="row mt-2 mx-auto">
        <div class="col-12 text-center">
            <a href="<?= Url::to(['site/toggle-dark-mode', 'flag' => $flag]) ?>" class="dropdown-item notify-item text-center">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input theme-choice"
                           id="dark-mode-switch" <?= $checked ?>>
                    <label class="custom-control-label" for="dark-mode-switch">Dark Mode</label>
                </div>
            </a>
        </div>
    </div>

</div>

<?php
$urlValidateReferralCode = Url::to(['/member/validate-referral-code']);
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
    
    function actionStep() {
        /** verify referral */
        const referral = $('#referral_code').val();
        if (referral == null || referral == '') {
            alert('Referral tidak boleh kosong');
            return false;
        }
        
        let myPromise = new Promise(function(myResolve, myReject) {
            $.ajax({
                type: "POST",
                url: "{$urlValidateReferralCode}",
                data: {
                    "{$csrfParam}": "{$csrfToken}",
                    'referral_code': $('#referral_code').val()
                },
                success: function(response) {
                    myResolve(response);
                },
                error: function(error) {
                    myReject(error);
                }
            });
        });
        
        myPromise.then(
            function(value) {
                console.log(value);
                if (value?.status == 'success') {
                    $('a[href="#next"]').show(); 
                    $('#referral_code').attr('readonly', true);
                    setTimeout(() => {
                        $('a[href="#next"]').trigger('click');
                    }, 300);
                } else {
                    alert(value?.message);
                }               
          },
            function(error) {
                console.log(error);
            }
        );
    }
    
    $("#example-basic").steps({
        labels: {
            finish: "Submit" // Change this to your desired label for the last step's finish button
        },
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex) {
            // return actionStep(event, currentIndex, newIndex);
            let allButton = $('.btn-action');
            allButton.each(function() {
                const bIndex = $(this).data('index');
                if (bIndex <= newIndex) {
                    $(this).removeClass('disabled');
                } else {
                    if ($(this).hasClass('disabled') == false) {
                        $(this).addClass('disabled');
                    }
                }
            })
            return true;
        }
    });
    
    $('ul[role="tablist"]').hide();
    
    $(document).ready(function() {
        // $('a[href="#next"]').hide(); 
        
        $('#email').on('keyup', function() {
            const value = $(this).val();
    
            if (value.search('@') >= 0) {
                generateUsername();
            }
        })
        
        $('a[href="#finish"]').on('click', function() {
            $('#btn-register-submit').trigger('click');
        })
        
        $(document).ready(function() {
            $('#dark-mode-switch').on('change', function() {
                $(this).parent().trigger('click');
            })        
        })
    })


JS;

$this->registerJs($script, View::POS_END);

$style = <<<CSS
    body.enlarged {
        min-height: auto!important;
    }
    
    .wizard > .content {
        border: unset;
        min-height: auto !important;
        margin-bottom: 2rem;
    }
    
    .wizard > .actions  {
        padding-left: 2rem; 
        padding-right: 2rem;
    }
    
    ul[role=tablist] {
        display: none;
    }
    
    @media (min-width: 768px) {

        #register-form {
            display: block;
            width: 60%;
        }
    }

CSS;

$this->registerCss($style);

?>