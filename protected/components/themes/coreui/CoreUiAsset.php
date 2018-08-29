<?php

namespace app\components\themes\coreui;

use yii\web\AssetBundle;


/**
 * 
 */
class CoreUiAsset extends AssetBundle
{
	public $sourcePath = '@vendor/coreui/coreui/dist';
	public $css = [
		'css/bootstrap.css',
		'css/coreui.css',
		'css/coreui-standalone.css'
	];

	public $js = [
		'js/coreui.js',
		'js/coreui-utilities.js'
	];

	/*public $depends = [
		'rmrevin\yii\fontawesome\AssetBundle',
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];*/
}