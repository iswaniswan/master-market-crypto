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
    <div class="col-md-6" style="max-width: 20rem">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
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
        <div class="card mb-0" style="box-shadow: 0px 0px 35px 10px rgba(73,80,87,.15) !important">
            <div class="card-header bg-indigo">
                <div class="text-center">
                    <div class="my-3">
                        <a href="#">
                            <img src="<?= Yii::getAlias('@web').'/images/LOGO.png' ?>" style="width:100%">
                        </a>
                        
                        <!-- <h3 class="text-center text-white">AUTOCAREPRO</h3> -->
                        <!-- <h4 class="text-center text-white"><?= strtoupper(@$area) ?></h4> -->
                    </div>
                    <!-- <h5 class="text-white text-uppercase py-3 font-16"><?= Html::encode($this->title) ?></h5> -->
                    <p class="text-pink">Please fill out the following fields</p>
                </div>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"col-12 checkbox checkbox-purple ml-2 \">{input} {label}</div>\n<div class=\"col-12\">{error}</div>",
                ]) ?>

                <div class="" style="padding: 0.5rem 0rem;">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-outline-purple', 'name' => 'login-button']) ?>
                </div>
            </div> <!-- end card-body -->
        </div>
        <?php ActiveForm::end(); ?>
        <!-- end card -->
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="">Don't have an account? <a href="<?= \yii\helpers\Url::to(['/site/register']) ?>" class="text-purple ml-1"><b>Register</b></a></p>
            </div> <!-- end col -->
        </div>
        <!-- end row -->
        <?php 
        $checked = ''; $flag = false;
        $cookie = Yii::$app->request->cookies->getValue('dark-mode');
        if ($cookie != null and $cookie == true) {
            $checked = 'checked';
            $flag = true;
        }
        ?>                        
        <a href="<?= Url::to(['site/toggle-dark-mode', 'flag' => $flag]) ?>" class="dropdown-item notify-item text-center">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input theme-choice" 
                    id="dark-mode-switch" <?= $checked ?>>
                <label class="custom-control-label" for="dark-mode-switch">Dark Mode</label>
            </div>
        </a> 
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



