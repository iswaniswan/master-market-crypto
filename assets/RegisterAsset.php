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
class RegisterAsset extends AssetBundle
{
    public $sourcePath = '@themes/uplon/assets';
    public $css = [
    ];
    public $js = [
        'js/register/jquery.steps.min.js',
        'js/register/jquery.validate.min.js',
        'js/register/form-wizard.init.js'
    ];
    public $depends = [
        JqueryAsset::class,
        UplonAsset::class
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
