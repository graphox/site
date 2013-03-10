<?php

namespace Graphox\Timeline;

interface IVerbRender
{
	
	public function renderTitle(IVerb $verb);
	public function renderBody(IVerb $verb);
	public function renderJson(IVerb $verb);
}

