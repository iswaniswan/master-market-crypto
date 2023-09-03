<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
\app\assets\UplonAsset::register($this);

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

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="" style="margin-top: 35px">

        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
            <?= Alert::widget([
                'closeButton' => ['class' => 'close'],
                'options' => [
                        'style' => 'padding: 15px; 
                                    position: absolute; 
                                    width: fit-content;
                                    top: 5px; 
                                    right: 5px;
                                    text-align: right;'
                ]
            ]) ?>
        <?php } ?>

        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <?= $content ?>

            </div> <!-- end container-fluid -->

        </div> <!-- end content -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
