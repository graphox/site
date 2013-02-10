<?php

namespace Graphox\Modules\Admin\Widgets;

class EditConfig extends \CWidget
{
	public $form;
	public $noBox = false;
	
	public function run()
	{
		if(!isset($this->form) || $this->form === null)
		{
			$this->form = new \Graphox\Modules\Admin\Forms\EditConfig();
		}
		
		$this->render('editConfig', array('formBuilderModel' => $this->form, 'noBox' => $this->noBox));
	}
}

