<?php

class AsPurifiedMarkup extends AsMarkupType
{
	/**
	 * @attribute data the data to render
	 * @return the rendered html output
	 */
	public function render($data)
	{
		$p = new CHtmlPurifier;
		
		if(!empty($this->settings))
			$p->settings = json_decode($this->settings);
		
		return $p->purify($content);
	}
}
