<?php
/**
 * Created by Roman Revin <xgismox@gmail.com>.
 * Date: 03.02.12 11:24
 */

interface QtzPanelInterface
{
	public function separator();
	public function raw($html);
	public function single($label, $url = '#', $icon = false, $cssClass = false, $htmlOptions = array(), $linkHtmlOptions = array());
	public function stack($items, $cssClass = false, $htmlOptions = array());

	public function push($element);
	public function pushOnBar($html, $htmlOptions = array());
}