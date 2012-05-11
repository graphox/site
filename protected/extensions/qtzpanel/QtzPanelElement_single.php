<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 31.01.12 14:53
 */

class QtzPanelElement_single extends QtzPanelElement
{
	private $_data = array();

	private $_htmlOptions = array();
	private $_cssClass = 'single';

	public function __construct($label, $url = '#', $icon = false, $cssClass = false, $htmlOptions = array(), $linkHtmlOptions = array())
	{
		if(is_array($label)){
			$data = $label;
			$label = $data['label'];
			$url = isset($data['url']) ? $data['url'] : $url;
			$icon = isset($data['icon']) ? $data['icon'] : $icon;
			$cssClass = isset($data['cssClass']) ? $data['cssClass'] : $cssClass;
			$htmlOptions = isset($data['htmlOptions']) ? $data['htmlOptions'] : $htmlOptions;
			$linkHtmlOptions = isset($data['linkHtmlOptions']) ? $data['linkHtmlOptions'] : $linkHtmlOptions;
		}
		$this->_data = array(
			'label' => $label,
			'url' => $url,
			'icon' => $icon,
			'htmlOptions' => $linkHtmlOptions
		);
		$this->_htmlOptions = $htmlOptions;
		if (!empty($cssClass)) $this->_cssClass = $cssClass;
	}

	public function render()
	{
		$i = $this->_data['icon'];
		$l = $this->_data['label'];
		$u = $this->_data['url'];

		parent::appendOption($this->_htmlOptions, 'class', $this->_cssClass);

		echo CHtml::openTag('li', $this->_htmlOptions);
		if (!empty($i)) {
			if (is_array($i)) {
				$img = CHtml::tag('img', $i, '');
			} else {
				$img = CHtml::image($i, CHtml::encode($l));
			}
			$l = $img . CHtml::tag('div',array(),$l);
		}
		echo CHtml::link($l, $u, $this->_data['htmlOptions']);
		echo CHtml::closeTag('li');
	}
}