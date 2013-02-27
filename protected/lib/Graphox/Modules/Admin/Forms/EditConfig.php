<?php

namespace Graphox\Modules\Admin\Forms;

class EditConfig extends \CFormModel
{
	protected static $SETTINGS = array(
		'site.name' => 'siteName',
		'site.theme' => 'siteTheme',
	);
	
	public $siteName;
	public $siteTheme;
	
	public function rules()
	{
		return array(
			array('siteName, siteTheme', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'siteName' => \Yii::t('admin.config', 'The name of the site.'),
			'siteTheme' => \Yii::t('admin.config', 'The site theme\'s name.')
		);
	}
	
	public function getFormConfig()
	{
		return array(
			'title' => 'Edit config',
			'showErrorSummary' => true,
			'action' => array( '/admin/default/settings' ),
			'elements' => array(
				'siteName' => array(
					'type' => 'text',
					'label'=> \Yii::t('admin.config', 'The name of the site.')
				),
				'siteTheme' => array(
					'type' => 'text',
					'label'=> \Yii::t('admin.config', 'The name of the site\'s theme.')
				)				
			),
			
			 'buttons' => array(
				 'submit' => array(
					'type'			=> 'submit',
					'layoutType'	=> 'primary',
					'label'			=> \Yii::t('admin.config', 'Save config.'),
				)
			),
		);
	}
	
	public function init()
	{
		$cfg = require (\Yii::getPathOfAlias('application.config.cfg').'.php');
		
		foreach(self::$SETTINGS as $name => $setting)
		{
			if(isset($cfg[$name]))
				$this->$setting = $cfg[$name];
		}
	}
	
	public function save()
	{
		$template = 
'<?php

/**
 * Configuration of the site, please use the web interface to change it.
 */
 
return ';
		$cfg = require (\Yii::getPathOfAlias('application.config.cfg').'.php');
		
		if(!is_array($cfg))
			$cfg = array();
		
		foreach(self::$SETTINGS as $name => $setting);
		{
			if($this->$setting !== null)
				$cfg[$name] = $this->$setting;
		}
		
		$template .= var_export($cfg, true);
		
		$template .= ';';
		
		\copy(\Yii::getPathOfAlias('application.config.cfg').'.php', \Yii::getPathOfAlias('application.config.cfg').'.bak.php');
		return file_put_contents(
			\Yii::getPathOfAlias('application.config.cfg').'.php',
			$template
		);
	}
}

