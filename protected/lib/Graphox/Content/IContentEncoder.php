<?php

namespace Graphox\Content;

/**
 * These classes should be used to proccess user data into content that is safe to display.
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

