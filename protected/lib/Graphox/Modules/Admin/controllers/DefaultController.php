<?php

namespace Graphox\Modules\Admin\Controllers;

use \Yii;

function checkCaptchaSupport()
{
	if(extension_loaded('imagick'))
	{
		$imagick=new Imagick();
		$imagickFormats=$imagick->queryFormats('PNG');
	}
	if(extension_loaded('gd'))
		$gdInfo=gd_info();
	if(isset($imagickFormats) && in_array('PNG',$imagickFormats))
		return '';
	elseif(isset($gdInfo))
	{
		if($gdInfo['FreeType Support'])
			return true;
	}
	return false;
}
		
class DefaultController extends \Controller
{
	
	public $layout='//layouts/column2';
	
	public $menu = array(
		array('label'=> 'Home', 'url'=>array('index')),
		array('label'=> 'Info', 'url'=>array('info')),
		array('label'=> 'Requirements', 'url'=>array('requirements')),
	);

	public function actionIndex()
	{
		//$users = \Graphox\Modules\User\User::model()->findAll();
		
		//var_dump($users);
		
		$this->render('overview');
	}
	
	public function actionInfo()
	{
		$info = array();
		
		ob_start();
		phpinfo();
		$infoLines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
		
		$cat = "General";
		foreach($infoLines as $line)
		{
			preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
			if(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
			{
				$info[$cat][$val[1]] = $val[2];
			}
				elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
				{
					$info[$cat][$val[1]] = array("local" => $val[2], "master" => $val[3]);
				}
			}
			
		
		$this->render('info', array('info' => $info));
	}
	
	public function actionRequirements()
	{
		$requirements = array(
			array(
				Yii::t('admin.requirements','PHP version'),
				version_compare(PHP_VERSION,"5.1.0",">="),
				Yii::t('admin.requirements','PHP 5.1.0 or higher is required.')
			),
			array(
				Yii::t('admin.requirements','Reflection extension'),
				class_exists('Reflection',false),
				Yii::t('admin.requirements', 'Reflection class extension is required.')
			),
			array(
				Yii::t('admin.requirements','PCRE extension'),
				extension_loaded("pcre"),
				Yii::t('admin.requirements', 'PCRE extension is required.')
			),
			array(
				Yii::t('yii','SPL extension'),
				extension_loaded("SPL"),
				Yii::t('admin.requirements', 'SPL extension is required.')
			),
			array(
				Yii::t('yii','DOM extension'),
				class_exists("DOMDocument",false),
				'Dom'
			),
			array(
				Yii::t('yii','Mcrypt extension'),
				extension_loaded("mcrypt"),
				Yii::t('yii','This is required by encrypt and decrypt methods.')),
			array(
				Yii::t('yii','GD extension with FreeType support or ImageMagick extension with PNG support'),
				checkCaptchaSupport(),
				Yii::t('admin.requirements', 'GD support is required.')
			),
				
			array(
				Yii::t('yii','Ctype extension'),
				extension_loaded("ctype"),
				Yii::t('admin.requirements', 'Ctype extention is required.')
			)
		);
		
		$this->render('requirements', array('requirements' => $requirements));
	}
}