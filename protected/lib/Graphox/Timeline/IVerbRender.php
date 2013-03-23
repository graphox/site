<?php

/**
 * Render for verbs
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

/**
 * Base rebder for Verbs
 * @package Graphox\Timeline
 */
interface IVerbRender
{

    /**
     * Renders the title of a verb into a string.
     * @param \Graphox\Timeline\IVerb $verb
     * @return string
     */
    public function renderTitle(IVerb $verb);

    /**
     * Renders the body of a verb into a string.
     * @param \Graphox\Timeline\IVerb $verb
     * @return string
     */
    public function renderBody(IVerb $verb);

    /**
     * Renders a verb into json.
     * @param \Graphox\Timeline\IVerb $verb
     * @return string
     */
    public function renderJson(IVerb $verb);
}

