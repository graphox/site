<?php

class AsMarkdownMarkup extends AsMarkupType
{
	/**
	 * @attribute data the data to render
	 * @return the rendered html output
	 */
	public function render($data)
	{
		$m = new CMarkdownParser;
		
		if(!empty($this->settings))
			foreach(json_decode($this->settings) as $key => $setting)
				$m->$key = $setting;
		
		return $m->safeTransform($content);
	}
}
