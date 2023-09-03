<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\assets\UplonAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\Cookie;

AppAsset::register($this);

/** cookie check */
$request = Yii::$app->request;
$darkMode = $request->cookies->getValue('dark-mode');

if ($darkMode != null and $darkMode == true) {
    \app\assets\UplonAssetDark::register($this);
    
    $cookie = new Cookie([
        'name' => 'dark-mode',
        'value' => true,
        'expire' => time() + 3600, // Cookie expiration time (in seconds)
    ]);
} else {
    \app\assets\UplonAsset::register($this);

    $cookie = new Cookie([
        'name' => 'dark-mode',
        'value' => false,
        'expire' => time() + 3600, // Cookie expiration time (in seconds)
    ]);
}
Yii::$app->response->cookies->add($cookie);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>

<?php
$js=<<< JS
     $(".alert").animate({opacity: 1.0}, 1500).fadeOut("slow");
JS;

$this->registerJs($js, yii\web\View::POS_READY);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <?= $this->render('header') ?>
    <!-- end Topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    <?= $this->render('sidebar') ?>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">

        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
            <?= Alert::widget([
                'closeButton' => ['class' => 'close'],
                'options' => [
                        'style' => 'padding: 15px; 
                                    position: absolute; 
                                    width: fit-content;
                                    top: 153px; 
                                    right: 30px;
                                    text-align: right;
                                    z-index: 9999'
                ]
            ]) ?>
        <?php } ?>

        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <?= $content ?>

            </div> <!-- end container-fluid -->

        </div> <!-- end content -->

        <!-- Footer Start -->
        <?= $this->render('footer') ?>
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<!-- Right Sidebar -->
<?php /*
<div class="right-bar">
    <div class="rightbar-title">
        <a href="javascript:void(0);" class="right-bar-toggle float-right">
            <i class="mdi mdi-close"></i>
        </a>
        <h4 class="font-18 m-0 text-white">Theme Customizer</h4>
    </div>
    <div class="slimscroll-menu">

        <div class="p-4">
            <div class="alert alert-warning" role="alert">
                <strong>Customize </strong> the overall color scheme, layout, etc.
            </div>
            <div class="mb-2">
                <img src="assets/images/layouts/light.png" class="img-fluid img-thumbnail" alt="">
            </div>
            <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input theme-choice" id="light-mode-switch" checked />
                <label class="custom-control-label" for="light-mode-switch">Light Mode</label>
            </div>

            <div class="mb-2">
                <img src="assets/images/layouts/dark.png" class="img-fluid img-thumbnail" alt="">
            </div>
            <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input theme-choice" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css"
                       data-appStyle="assets/css/app-dark.min.css" />
                <label class="custom-control-label" for="dark-mode-switch">Dark Mode</label>
            </div>

            <div class="mb-2">
                <img src="assets/images/layouts/rtl.png" class="img-fluid img-thumbnail" alt="">
            </div>
            <div class="custom-control custom-switch mb-5">
                <input type="checkbox" class="custom-control-input theme-choice" id="rtl-mode-switch" data-appStyle="assets/css/app-rtl.min.css" />
                <label class="custom-control-label" for="rtl-mode-switch">RTL Mode</label>
            </div>

            <a href="https://1.envato.market/XY7j5" class="btn btn-danger btn-block mt-3" target="_blank"><i class="mdi mdi-download mr-1"></i> Download Now</a>
        </div>
    </div> <!-- end slimscroll-menu-->
</div>
<!-- /Right-bar -->

<!-- Right bar overlay-->
<!--<div class="rightbar-overlay"></div>-->
<!---->
<!--<a href="javascript:void(0);" class="right-bar-toggle demos-show-btn">-->
<!--    <i class="mdi mdi-settings-outline mdi-spin"></i> &nbsp;Choose Demos-->
<!--</a>-->
*/ ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
