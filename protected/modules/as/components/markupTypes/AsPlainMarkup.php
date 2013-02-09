<?php

class AsPlainMarkup extends AsMarkupType
{
	/**
	 * @attribute data the data to render
	 * @return the rendered html output
	 */
	public function render($data)
	{
		return CHtml::encode($data);
	}
}
