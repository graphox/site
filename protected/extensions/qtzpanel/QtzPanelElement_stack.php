<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 31.01.12 14:58
 */

class QtzPanelElement_stack extends QtzPanelElement
{
	private $_data = array();

	private $_htmlOptions = array();
	private $_cssClass = 'stack';

	public function __construct($cssClass = false, $htmlOptions = array())
	{
		if (is_array($cssClass)) {
			$data = $cssClass;
			$this->_data = isset($data['items']) ? $data['items'] : array();
			$cssClass = isset($data['cssClass']) ? $data['cssClass'] : $cssClass;
			$htmlOptions = isset($data['htmlOptions']) ? $data['htmlOptions'] : $htmlOptions;
		}
		$this->_htmlOptions = $htmlOptions;
		if (!empty($cssClass)) $this->_cssClass = $cssClass;
	}

	public function push($label, $url = '#', $icon = false, $htmlOptions = array())
	{
		array_push($this->_data, array(
			'label' => $label,
			'url' => $url,
			'icon' => $icon,
			'htmlOptions' => $htmlOptions
		));
	}

	public function render()
	{
		parent::appendOption($this->_htmlOptions, 'class', $this->_cssClass);

		echo CHtml::openTag('li', $this->_htmlOptions);
		if (!empty($this->_data)) {
			echo '<ul>';
			foreach ($this->_data as $item) {
				$i = $item['icon'];
				$l = $item['label'];
				$u = $item['url'];
				echo '<li>';
				if (!empty($i)) {
					if (is_array($i)) {
						$img = CHtml::tag('img', $i, '');
					} else {
						$img = CHtml::image($i, CHtml::encode($l));
					}
					$l = $img . $l;
				}
				echo CHtml::link($l, $u, @$item['htmlOptions']);
				echo '</li>';
			}
			echo '</ul>';
		}

		echo CHtml::closeTag('li');
	}
}