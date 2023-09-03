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
class UplonAsset extends AssetBundle
{
    public $sourcePath = '@themes/uplon/assets';
    public $css = [
        'css/bootstrap.min.css',
        'css/icons.css',
        'css/app.css',
        'css/custom.css'
    ];
    public $js = [
        'js/vendor.min.js',
//        'libs/morris-js/morris.min.js',
//        'libs/raphael/raphael.min.js',
//        'js/pages/dashboard.init.js',
        'js/app.min.js',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap5\BootstrapAsset',
//        DataTableAsset::class
    ];
    public $publishOptions = [
        'only' => [
            'css/*',
            'fonts/*',
            'js/*',
        ]
    ];
}
