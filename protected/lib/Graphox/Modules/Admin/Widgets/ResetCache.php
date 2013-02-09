<?php

namespace Graphox\Modules\Admin\Widgets;

class ResetCache extends \CWidget
{
	public $form;
	public $noBox = false;
	
	public function run()
	{
		if(!isset($this->form) || $this->form === null)
		{
			$this->form = new \Graphox\Modules\Admin\Forms\ResetCache();
		}
		
		$this->render('resetCache', array('formBuilderModel' => $this->form, 'noBox' => $this->noBox));
	}
}

