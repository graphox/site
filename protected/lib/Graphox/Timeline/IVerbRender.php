<?php

namespace Graphox\Timeline;

interface IVerbRender
{
	public function renderHTML(IVerb $verb);
	public function renderText(IVerb $verb);
	public function renderJson(IVerb $verb);
}

