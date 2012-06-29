<?php

/*
	Area in pages to store text / widgets in,
	changable in db (=> TODO)

*/

class UIDbWidgetArea extends AsWidget
{
	public $name = '';
	
	public function init()
	{
		parent::init();

		ob_start();
		ob_implicit_flush(false);
	}
	
	public function setTitle($value)
	{
		$this->name = $value;
	}
	
	public function run()
	{
		$result = ob_get_clean();
		
		$id = md5($result);
		
		#TODO: search db on hash of result
		
		//if( hasaccess..) {
		$this->name .= CHtml::link(CHtml::openTag('span', array('class' => 'icon icon-page_gear')).CHtml::closeTag('span'), array('//as/widget/edit', 'id' => $id), array());
		//}
		
		
		$this->beginWidget('zii.widgets.CPortlet', array('title' => $this->name));
		echo $result;
		$this->endWidget();		

	}
}
