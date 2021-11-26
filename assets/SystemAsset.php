<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SystemAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css.php',
        'jqwidgets/styles/jqx.base.css',
        'css/dataTables.bootstrap.css',
        'introjs/introjs.css',
    ];
    public $js = [
        'js/bootstrap-filestyle.min.js',
        'jqwidgets/jqxcore.js',
        'jqwidgets/jqxdata.js',
        'jqwidgets/jqxbuttons.js',
        'jqwidgets/jqxscrollbar.js',
        'jqwidgets/jqxpanel.js',
        'jqwidgets/jqxtree.js',
        'jqwidgets/jqxdatetimeinput.js',
        'jqwidgets/jqxcalendar.js',
        'jqwidgets/globalization/globalize.js',
        'js/jquery.form.js',
        'js/jquery.dataTables.min.js',
        'js/dataTables.bootstrap.min.js',
        'introjs/intro.js',   
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'dmstr\web\AdminLteAsset'
    ];
}
