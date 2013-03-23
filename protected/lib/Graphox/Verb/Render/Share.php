<?php

/**
 * Share verb render
 * @package Graphox\Verb\Render
 * @author killme
 */

namespace Graphox\Verb\Render;

use Graphox\Timeline\IVerb;
use Graphox\Verb\Share as BaseShare;

/**
 * Share verb render
 * @package Graphox\Verb\Render
 */
class Share implements \Graphox\Timeline\IVerbRender
{

    /**
     * {@inheritdoc}
     */
    public function renderTitle(IVerb $verb)
	{
		return false;
	}

    /**
     * {@inheritdoc}
     */
    public function renderBody(IVerb $verb)
	{
		assert($verb instanceof BaseShare);

		return $verb->getContent();
	}

    /**
     * {@inheritdoc}
     */
    public function renderJson(IVerb $verb)
	{
		assert($verb instanceof BaseShare);

		return json_encode(array(
			'createdDate'	=> $verb->getCreatedDate()->format(\DateTime::RFC3339),
			'source'		=> $verb->getSource(),
			'content'		=> $verb->getContent(),
			'isPublished'	=> $verb->isPublished(),
			'isDeleted'		=> $verb->isDeleted()
		));
	}
}

