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
class Select2Asset extends AssetBundle
{
    public $sourcePath = '@themes/uplon/assets/libs';
    public $css = [
        'select2/select2.min.css',
    ];
    public $js = [
        'select2/select2.min.js'
    ];
    public $depends = [
    //    'yii\web\YiiAsset',
        JqueryAsset::class,
        UplonAsset::class
    ];
    // public $publishOptions = [
    //     'only' => [
    //         'css/*',
    //         'js/*',
    //     ]
    // ];
}
