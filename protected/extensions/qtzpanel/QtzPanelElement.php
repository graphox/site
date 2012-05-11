<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 31.01.12 14:39
 */

abstract class QtzPanelElement
{
	public function appendOption(&$htmlOptions, $option, $value)
	{
		$htmlOptions[$option] = isset($htmlOptions[$option]) ? $htmlOptions[$option] . ' ' : '';
		$htmlOptions[$option] .= $value;
	}

	abstract public function render();
}