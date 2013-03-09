<?php

namespace Graphox\Verb;

use \Graphox\Timeline\Verb,
	\Graphox\Content\IHaveContent;

class Share extends Verb implements IHaveContent
{

	/**
	 * @property string $content The encoded version of the user generated source.
	 * @OGM\property
	 */
	protected $content;

	/**
	 * @property string $source The unencoded source.
	 * @OGM\property
	 */
	protected $source;

	/**
	 * {@inheritdoc}
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setContent($content)
	{
		$this->content = (string) $content;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSource()
	{
		return $this->source;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setSource($source)
	{
		$this->source = (string) $source;
	}

}

