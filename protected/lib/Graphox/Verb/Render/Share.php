<?php
namespace Graphox\Verb\Render;

use Graphox\Timeline\IVerb;
use Graphox\Verb\Share as BaseShare;

class Share implements \Graphox\Timeline\IVerbRender
{
	public function renderTitle(IVerb $verb)
	{
		return false;
	}
	
	public function renderBody(IVerb $verb)
	{
		assert($verb instanceof BaseShare);
		
		return $verb->getContent();
	}
	
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

