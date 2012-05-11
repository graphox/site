<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 31.01.12 14:50
 */

class QtzPanelElement_separator extends QtzPanelElement
{
	private $_htmlOptions = array();
	private $_cssClass = 'separator';

	public function __construct($cssClass = false, $htmlOptions = array())
	{
		if(is_array($cssClass)){
			$data = $cssClass;
			$cssClass = isset($data['cssClass']) ? $data['cssClass'] : false;
			$htmlOptions = isset($data['htmlOptions']) ? $data['htmlOptions'] : $htmlOptions;
		}
		$this->_htmlOptions = $htmlOptions;
		if (!empty($cssClass)) $this->_cssClass = $cssClass;
	}

	public function render()
	{
		parent::appendOption($this->_htmlOptions, 'class', $this->_cssClass);
		echo CHtml::tag('li', $this->_htmlOptions);
	}
}