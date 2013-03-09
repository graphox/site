<?php

namespace Graphox\Content;

/**
 * @todo pass \CHtmlpurifier instance / config array in constructor
 */
class HtmlPurifier implements IContentEncoder
{

	/**
	 * Retuns the instance to a static purifier instance
	 * @staticvar \CHtmlPurifier $p
	 * @return \CHtmlPurifier
	 */
	protected function getPurifier()
	{
		static $p;

		if (!isset($p))
		{
			$p = new \CHtmlPurifier;
		}

		return $p;
	}

	/**
	 * {@inheritdoc}
	 */
	public function encodeContent(IHaveContent $content)
	{
		$content->setContent(
				$this->encodeString(
						$content->getSource()
				)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function encodeString($string)
	{
		return $this->getPurifier()->purify($string);
	}

}

