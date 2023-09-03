<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class XeditableAsset extends AssetBundle
{
    public $sourcePath = '@themes/uplon/assets';
    public $css = [
        'libs/x-editable/bootstrap-editable.css',
    ];
    public $js = [
        'libs/x-editable/bootstrap-editable.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
        UplonAsset::class
    ];
//    public $publishOptions = [
//    ];
}
