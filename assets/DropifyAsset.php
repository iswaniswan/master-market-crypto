<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DropifyAsset extends AssetBundle
{
    public $sourcePath = '@themes/uplon/assets/libs';
    public $css = [
        'dropify/dropify.min.css',
    ];
    public $js = [
        'dropify/dropify.min.js'
    ];
    public $depends = [
        JqueryAsset::class,
        UplonAsset::class
    ];

    // public $publishOptions = [
    //     'only' => [
    //         'css/*',
    //         'fonts/*',
    //         'js/*',
    //         'libs/*'
    //     ]
    // ];
}
