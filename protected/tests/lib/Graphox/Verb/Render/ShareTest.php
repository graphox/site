<?php

namespace Graphox\Verb\Render;

use Graphox\Verb\Share as ShareVerb;

class ShareTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider verb
	 * @covers \Graphox\Verb\Render\Share::renderJson
	 * 
	 * @param ShareVerb $verb
	 */
	public function testRenderJson($verb)
	{
		$render = new \Graphox\Verb\Render\Share;
		$json = $render->renderJson($verb);
		
		$arr = json_decode($json);
		
		$this->assertNotNull($arr);
		
		if($arr !== null)
		{
			$this->assertNotEmpty($arr->content);
			$this->assertEquals($arr->content, $verb->getContent());
			
			$this->assertNotEmpty($arr->source);
			$this->assertEquals($arr->source, $verb->getSource());
			
			$this->assertNotEmpty($arr->createdDate);
			$this->assertEquals($arr->createdDate, $verb->getCreatedDate()->format(\DateTime::RFC3339));
			
			$this->assertEquals($arr->isPublished, $verb->getIsPublished());
			$this->assertEquals($arr->isDeleted, $verb->getIsDeleted());
		}
	}
	
	/**
	 * @dataProvider verb
	 * @covers \Graphox\Verb\Render\Share::renderTitle
	 * 
	 * @param ShareVerb $verb
	 */
	public function testRenderTitle($verb)
	{
		$render = new Share;
		$this->assertEquals(false, $render->renderTitle($verb));
	}
	
	/**
	 * @dataProvider verb
	 * @covers \Graphox\Verb\Render\Share::renderBody
	 * 
	 * @param ShareVerb $verb
	 */
	public function testRenderBody($verb)
	{
		$render = new Share;
		$this->assertEquals($verb->getContent(), $render->renderBody($verb));
	}
	
	public function verb()
	{
		$info[] = array(
			'content' => 'Hello world how are ya?',
			'source' => 'Hello world how are ya?',
			'createdDate' => new \DateTime(),
			'isPublished' => false,
			'isDeleted' => false,
		);

		$info[] = array(
			'content' => 'Hello world how are ya?',
			'source' => 'Hello world how are ya?',
			'createdDate' => new \DateTime(),
			'isPublished' => true,
			'isDeleted' => false,
		);
		
		$info[] = array(
			'content' => 'Hello world how are ya?',
			'source' => 'Hello world how are ya?',
			'createdDate' => new \DateTime(),
			'isPublished' => false,
			'isDeleted' => true,
		);
		
		$info[] = array(
			'content' => 'Hello world how are ya?',
			'source' => 'Hello world how are ya?',
			'createdDate' => new \DateTime(),
			'isPublished' => true,
			'isDeleted' => true,
		);
		
		$arr = array();
		
		foreach($info as $i)
		{
			$r = new ShareVerb;
			$r->setContent($i['content']);
			$r->setSource($i['source']);
			$r->setCreatedDate($i['createdDate']);
			$r->setIsPublished($i['isPublished']);
			$r->setIsDeleted($i['isDeleted']);
			
			$arr[] = array($r);
		}	
		
		return $arr;
	}
	
}

