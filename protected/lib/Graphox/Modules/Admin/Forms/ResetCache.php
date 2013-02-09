<?php

namespace Graphox\Modules\Admin\Forms;

class ResetCache extends \CFormModel
{
	public $sure;
	
	public function rules()
	{
		return array(
			array('sure', 'boolean')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'sure' => \Yii::t('admin.cache', 'Are you sure you want to reset the cache?')
		);
	}
	
	public function getFormConfig()
	{
		return array(
			'title' => 'Reset cache',
			'showErrorSummary' => true,
			'action' => array( '/admin/cache/reset' ),
			'elements' => array(
				'<p>Flushes all temporarily data</p>',
				'sure' => array(
					'type' => 'checkbox',
					'label'=> \Yii::t('admin.cache', 'Are you sure you want to reset the cache?')
				)
			),
			
			 'buttons' => array(
				 'submit' => array(
					'type'			=> 'submit',
					'layoutType'	=> 'primary',
					'label'			=> \Yii::t('admin.cache', 'Reset Cache'),
				)
			),
		);
	}
	
	private function cleanAssets($dir, $keepdir = false)
	{
		$files = array_diff(scandir($dir), array('.','..'));
		
		foreach ($files as $file)
		{
			$file = $dir.'/'.$file;
			
			if(is_dir($file))
				$this->cleanAssets($file);
			else
				unlink($file);
		}

		if(!$keepdir)
			return rmdir($dir);
		else
			return true;
	}
	
	public function resetCache()
	{
		if($this->validate())
		{
			if(!isset($this->sure) || ($this->sure !== true && $this->sure !== 1 && $this->sure !== '1'))
				$this->addError ('sure', \Yii::t('admin.cache', 'Please be sure'));
			else
			{
				\Yii::app()->cache->flush();
				$this->cleanAssets(\Yii::app()->assetManager->getBasePath(), true);
				return true;
			}
		}
		return false;
	}
}

