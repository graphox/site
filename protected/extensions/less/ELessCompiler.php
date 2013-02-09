<?php

include __DIR__.'/vendors/lessphp/lessc.inc.php';

class ElessCompiler extends CApplicationComponent
{
	public $env			= array();
	public $functions	= array();
	public $paths		= array();
	
	public $tmpDir;
	public $autoCompile = true;
	public $forceRecompile = false;
	public $compileDir = 'application.runtime.less';
	
	public $cache = 'cache';
	
	public function getTmpDir()
	{
		return Yii::getPathOfAlias($this->tmpDir);
	}
	
	public function getCompiler()
	{
		static $compiler;
		
		if(!isset($compiler))
		{
			$compiler = new lessc;
		}
		
		return $compiler;
	}
	
	public function getCache()
	{
		return Yii::app()->{$this->cache};
	}
	
	public function getCacheKey($file)
	{
		return 'less-'.md5($file);
	}
	
	public function getDestDir()
	{
		static $path;
		
		if(!isset($path))
		{
			$path = Yii::app()->assetManager->getBasePath().'/less';
			@mkdir ($path);
			@chmod($path, 0777);
		}
		
		return $path;
	}
	
	public function publish($file)
	{
		$file = Yii::getPathOfAlias($file).'.less';
		if(!$file)
			throw new CException('invalid path');
		$key = $this->getCacheKey($file);
		$destDir = $this->getDestDir();
		
		$destFile = $destDir.'/'.$key;		
		
		$less = $this->getCompiler();
		$less->setFormatter('compressed');
		$less->setVariables($this->env);
		
		if(!file_exists($destFile) || filemtime($destFile) < filemtime($file))
		{
			file_put_contents (
				$destFile,
				$less->compile(
					file_get_contents ($file)
				)
			);
			@chmod($destFile, 0666);			
		}		 
		return Yii::app()->assetManager->getBaseUrl().'/less/'.$key;
	}
	
	public function register($file)
	{
		return Yii::app()->clientScript->registerCssFile(
			$this->publish($file)
		);
	}
}
