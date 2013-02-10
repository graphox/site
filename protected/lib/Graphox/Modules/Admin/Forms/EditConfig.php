<?php

namespace Graphox\Modules\Admin\Forms;

class EditConfig extends \CFormModel
{
	public $cfg;
	
	public function rules()
	{
		return array(
			array('cfg', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'cfg' => \Yii::t('admin.config', 'This is the configuration of the site.')
		);
	}
	
	public function getFormConfig()
	{
		return array(
			'title' => 'Edit config',
			'showErrorSummary' => true,
			'action' => array( '/admin/default/settings' ),
			'elements' => array(
				'cfg' => array(
					'type' => 'textarea',
					'label'=> \Yii::t('admin.config', 'The configuration.')
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
		$this->cfg = file_get_contents(\Yii::getPathOfAlias('application.config.main').'.php');
	}
	
	public function save()
	{
		\copy(\Yii::getPathOfAlias('application.config.main').'.php', \Yii::getPathOfAlias('application.config.main').'.bak.php');
		return file_put_contents(
			\Yii::getPathOfAlias('application.config.main').'.php',
			$this->cfg
		);
	}
}

