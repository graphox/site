<?php

/**
 * Interface of classes that share user formatted content.
 * @package Graphox\Content
 * @author killme
 */

namespace Graphox\Content;

/**
 * User formatted content.
 */
interface IHaveContent
{

	/**
     * Returns the formatted content.
     * @return string
     */
	public function getContent();

	/**
	 * Sets the value of the content.
	 * @param string $content
	 */
	public function setContent($content);

	/**
     * Returns the raw source.
     * @return string
     */
	public function getSource();

	/**
	 * Sets the value of the source.
	 * @param string $source
	 */
	public function setSource($source);
}

