<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 31.01.12 14:45
 */

class QtzPanelElement_raw extends QtzPanelElement
{
	private $_data = array();

	private $_htmlOptions = array();
	private $_cssClass = 'raw';

	public function __construct($html, $cssClass=false, $htmlOptions=array())
	{
		if(is_array($html)){
			$data = $html;
			$html = $data['html'];
			$cssClass = isset($data['cssClass']) ? $data['cssClass'] : $cssClass;
			$htmlOptions = isset($data['htmlOptions']) ? $data['htmlOptions'] : $htmlOptions;
		}
		$this->_data = $html;
		$this->_htmlOptions = $htmlOptions;
		if (!empty($cssClass)) $this->_cssClass = $cssClass;
	}

	public function render($params=array())
	{
		$height = CPropertyValue::ensureInteger($params['height']);
		$height -= $height > 40 ? 20 : 0;
		parent::appendOption($this->_htmlOptions, 'class', $this->_cssClass);
		parent::appendOption($this->_htmlOptions, 'style', 'height: '.$height.'px;');
		echo CHtml::tag('li', $this->_htmlOptions, $this->_data);
	}
}