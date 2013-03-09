<?php

namespace Graphox\Content;

interface IHaveContent
{

	/**
	 * @return string returns $this->content
	 */
	public function getContent();

	/**
	 * Sets the value of the content.
	 * @param string $content
	 */
	public function setContent($content);

	/**
	 * @return string the source.
	 */
	public function getSource();

	/**
	 * Sets the value of the source.
	 * @param string $source
	 */
	public function setSource($source);
}

