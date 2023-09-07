<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\web\View;

\app\assets\UplonAsset::register($this);

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row mb-4 justify-content-center">
    <div class="col-8" style="">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
//                'template' => "{label}\n{input}\n{error}",
                'template' => "{label}\n{input}",
                'labelOptions' => ['class' => 'col-12 col-form-label', 'style' => 'font-weight: 400; padding-left: unset'],
                'inputOptions' => ['class' => 'col-12 form-control', 'style' => 'padding-right: 1rem'],
//                'errorOptions' => ['class' => 'col-12 invalid-feedback'],
                'horizontalCssClasses' => [
                    'field' => 'mb-3',
                ]
            ],
        ]); ?>
        <div class="card mb-0" style="box-shadow: 0px 0px 35px 35px rgba(73,80,87,.15) !important">
            <div class="card-body" style="padding: unset">
                <div class="row">
                    <div class="col-md-5" style="padding: 2rem">
                        <div class="logo logo-dark" >
                            <img src="<?= Yii::getAlias('@web').'/images/app-logo.png' ?>" style="width:100%; object-fit:scale-down">
                        </div>
                        <div class="logo logo-light" >
                            <img src="<?= Yii::getAlias('@web').'/images/app-logo-dark.png' ?>" style="width:100%; object-fit:scale-down">
                        </div>
                        <div class="text-center mb-5">
                            <h4 class="">Aplikasi Market Crypto</h4>
                        </div>
                        <div class="col-12 text-center" style="">
                            <p class="">Don't have an account?
                                <a href="<?= \yii\helpers\Url::to(['/site/register']) ?>" class="text-purple ml-1" style="display: block"><b>Register</b></a>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-7 bg-light" style="padding: 2rem;">
                        <h3>Account Login</h3>
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <div class="mb-3 field-loginform-rememberme">
                            <div class="col-12 checkbox checkbox-purple ml-2 ">
                                <input type="hidden" name="LoginForm[rememberMe]" value="0">
                                <input type="checkbox" id="loginform-rememberme" class="form-check-input" name="LoginForm[rememberMe]" value="1" checked="">
                                <label class="form-check-label" style="font-weight: 400;" for="loginform-rememberme">Remember Me</label>
                            </div>
                            <div class="col-12"><div class="invalid-feedback "></div></div>
                        </div>

                        <div class="" style="padding: 0.5rem 0rem;">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-outline-purple', 'name' => 'login-button']) ?>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
        <?php ActiveForm::end(); ?>
        <!-- end card -->

        <!-- end row -->
        <?php 
        $checked = ''; $flag = false;
        $cookie = Yii::$app->request->cookies->getValue('dark-mode');
        if ($cookie != null and $cookie == true) {
            $checked = 'checked';
            $flag = true;
        }
        ?>
        <div class="row mt-2">
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
$script = <<<JS

$(document).ready(function() {
    $('#dark-mode-switch').on('change', function() {
        $(this).parent().trigger('click');
    })        
})


JS;

$this->registerJs($script, View::POS_END);

?>



