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
class DataTableAsset extends AssetBundle
{
    public $sourcePath = '@themes/uplon/assets/libs';
    public $css = [
        'datatables/dataTables.bootstrap4.min.css',
        'datatables/buttons.bootstrap4.min.css',
        'datatables/responsive.bootstrap4.min.css',
        'datatables/select.bootstrap4.min.css',
    ];
    public $js = [
        'datatables/jquery.dataTables.min.js',
        'datatables/dataTables.bootstrap4.min.js',
        'datatables/dataTables.responsive.min.js',
        'datatables/responsive.bootstrap4.min.js',
        'datatables/dataTables.buttons.min.js',
        'datatables/buttons.bootstrap4.min.js',
        'jszip/jszip.min.js',
        'pdfmake/pdfmake.min.js',
        'pdfmake/vfs_fonts.js',
        'datatables/buttons.html5.min.js',
        'datatables/buttons.print.min.js',
        'datatables/dataTables.keyTable.min.js',
        'datatables/dataTables.select.min.js',
    ];
    public $depends = [
        UplonAsset::class,
        AppAsset::class
    ];

}
