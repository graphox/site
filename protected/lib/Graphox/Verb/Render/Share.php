<?php
namespace Graphox\Verb\Render;

class Share implements \Graphox\Timeline\IVerbRender
{
	public function renderHTML(IVerb $verb);
	public function renderText(IVerb $verb);
	public function renderJson(IVerb $verb);
}

