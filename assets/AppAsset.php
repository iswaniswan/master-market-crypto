<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/myDataTables.css'
//        'css/site.css',
//        'themes/uplon/assets/css/bootstrap.css',
//        'themes/uplon/assets/css/icons.css',
//        'themes/uplon/assets/css/app.css',
    ];
    public $js = [
//        'themes/uplon/assets/js/vendor.min.js',
//        'themes/uplon/assets/libs/morris-js/morris.min.js',
//        'themes/uplon/assets/libs/raphael/raphael.min.js',
//        'themes/uplon/assets/js/pages/dashboard.init.js',
//        'themes/uplon/assets/js/app.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap5\BootstrapAsset'
    ];
    public $publishOptions = [
        'only' => [
            'css/*',
            'fonts/*',
            'js/*',
        ]
    ];
}
