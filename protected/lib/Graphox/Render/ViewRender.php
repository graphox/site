<?php

/**
 * Renders basic php views.
 * @package Graphox\Render
 * @author killme
 */

namespace Graphox\Render;

/**
 * Renders basic php views
 * @package Graphox\Render
 */
class ViewRender
{

    /**
     * The rendermanager
     * @var RenderManager
     */
    private $manager;

    /**
     * Initalizes the viewrender and sets the RenderManager
     * @param \Graphox\Render\RenderManager $manager
     */
    public function __construct(RenderManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Returns the rendermanager that is used.
     * @return RenderManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Renders a view file to string.
     * @param \Graphox\Render\View $self
     * @return string
     */
    public function render(View $self)
    {
        ob_start();
        ob_implicit_flush(false);
        extract($self->getVars());
        require($self->getFile() . '.php');

        return ob_get_clean();
    }

}

