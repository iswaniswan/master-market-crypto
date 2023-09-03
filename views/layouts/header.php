<?php

use app\assets\UplonAsset;
use app\components\Session;
use app\models\Member;
use yii\helpers\Url;
use yii\web\UrlRule;
use yii\web\View;

 ?>


<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <?php 
                $member = Member::findOne(['id' => Session::getIdMember()]);
                $imageSource = $member->getLinkPhoto();
                ?>
                <img src="<?= $imageSource ?>" alt="user-image" class="rounded-circle">
                <span class="d-none d-sm-inline-block ml-1 font-weight-medium"><?= Session::getUsername() ?></span>
                <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <!-- <div class="dropdown-header noti-title">
                    <h6 class="text-overflow text-white m-0">Welcome !</h6>
                </div> -->

                <!-- item-->
                <a href="<?= Url::to(['member/update-profile', 'id' => Session::getIdMember()]) ?>" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-outline"></i>
                    <span>Profile</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-settings-outline"></i>
                    <span>Settings</span>
                </a>

                <!-- item-->
                <?php /*
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-lock-outline"></i>
                    <span>Lock Screen</span>
                </a> 
                */ ?>
                <?php 
                $checked = ''; $flag = false;
                $cookie = Yii::$app->request->cookies->getValue('dark-mode');
                if ($cookie != null and $cookie == true) {
                    $checked = 'checked';
                    $flag = true;
                }
                ?>                        
                <a href="<?= Url::to(['site/toggle-dark-mode', 'flag' => $flag]) ?>" class="dropdown-item notify-item">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input theme-choice" 
                            id="dark-mode-switch" <?= $checked ?>>
                        <label class="custom-control-label" for="dark-mode-switch">Dark Mode</label>
                    </div>
                </a> 


                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="<?= \yii\helpers\Url::to(['/site/logout']) ?>" data-method="post" class="dropdown-item notify-item">
                    <i class="mdi mdi-logout-variant"></i>
                    <span>Logout</span>
                </a>

            </div>
        </li>
    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <a href="<?= Url::to(['/dashboard']) ?>" class="logo text-center logo-light">
            <span class="logo-lg" style="">
                <img src="<?= Yii::getAlias('@web').'/images/LOGO.png' ?>" style="width:80%; max-height:64px; object-fit:scale-down">
                <!-- <span class="logo-lg-text-dark">Uplon</span> -->
            </span>
        </a>
        <a href="<?= Url::to(['/dashboard']) ?>" class="logo text-center logo-dark">
            <span class="logo-lg" style="">
                <img src="<?= Yii::getAlias('@web').'/images/LOGO.png' ?>" style="width:80%; max-height:64px; object-fit:scale-down">
                <!-- <span class="logo-lg-text-dark">Uplon</span> -->
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile waves-effect waves-light">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>
    </ul>
</div>


<?php 

$assetManager = $this->getAssetManager();
$bundle = $assetManager->getBundle(UplonAsset::className());
$cssBootstrap = $assetManager->getAssetUrl($bundle, 'css/bootstrap.min.css');
$cssBootstrapDark  = $assetManager->getAssetUrl($bundle, 'css/bootstrap-dark.min.css');
$cssApp = $assetManager->getAssetUrl($bundle, 'css/app.css');
$cssAppDark = $assetManager->getAssetUrl($bundle, 'css/app-dark.css');

$script = <<<JS

    $(document).ready(function() {
        $('#dark-mode-switch').on('change', function() {
            $(this).parent().trigger('click');
            // if ($(this).is(':checked')) {
            //     $('head').append('<link rel="stylesheet" type="text/css" href="$cssBootstrapDark">')
            //     $('head').append('<link rel="stylesheet" type="text/css" href="$cssAppDark">')
            // } else {
            //     $('link[rel=stylesheet][href~="$cssAppDark"]').remove();
            //     $('link[rel=stylesheet][href~="$cssBootstrapDark"]').remove();
            // }
        })        
    })


JS;

$this->registerJs($script, View::POS_END);

?>