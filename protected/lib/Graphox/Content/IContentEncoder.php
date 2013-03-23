<?php

/**
 * Content encoder to format user's unsafe input into nice html.
 * @package Graphox\Content
 * @author killme
 */

namespace Graphox\Content;

/**
 * Content encoder to format user's unsafe input into nice html.
 */
interface IContentEncoder
{

	/**
	 * Encodes a string
	 * @param string $string the string to encode
	 * @return strign the encoded string
	 */
	public function encodeString($string);

	/**
	 * Easy method to parse the source into content.
	 * @uses encodeString
	 * @param \Graphox\Content\IHaveContent $content the content to encode
	 */
	public function encodeContent(IHaveContent $content);
}

