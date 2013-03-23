<?php

/**
 * Share verb.
 * @author killme
 * @package Graphox\Verb\Share
 */

namespace Graphox\Verb;

use \Graphox\Timeline\Verb,
	\Graphox\Content\IHaveContent,
    \Graphox\Timeline\IHaveExecutor;
use HireVoice\Neo4j\Annotation as OGM;

/**
 * Share entity for short user messages.
 * Implements both IHaveContent and IHaveExecutor
 * @OGM\Entity
 */
class Share extends Verb implements IHaveContent, IHaveExecutor
{

	/**
     * The encoded version of the user generated source.
     * @property string $content
     * @OGM\property
     */
	protected $content;

	/**
     * The unencoded source.
     * @property string $source
     * @OGM\property
     */
	protected $source;

    /**
     * The user that has shared this.
     * @property \Graphox\Modules\User\User $executor
     * @OGM\ManyToOne
     */
    protected $executor;

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

    	/**
     * {@inheritdoc}
     */
    public function setExecutor(\Graphox\Modules\User\User $user)
    {
        $this->executor = $user;
    }

    	/**
     * {@inheritdoc}
     */
    public function getExecutor()
    {
        return $this->executor;
    }

}

