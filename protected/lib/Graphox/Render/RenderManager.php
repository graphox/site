<?php

/**
 * Manages the rendering of content.
 * Using layouts, views and themes.
 * @author killme
 * @package Graphox\Render
 */

namespace Graphox\Render;

/**
 * Manages the rendering of content.
 * Using layouts, views and themes.
 * @package Graphox\Render
 */
class RenderManager
{

    /**
     * View renders for different view types.
     * @var array
     */
    private $viewRenders = array(
            View::TYPE_PHP => 'Graphox\Render\ViewRender'
    );

    /**
     * Instance of the current theme class.
     * @todo implement
     * @var Theme
     */
    private $theme;

    /**
     * Locates the view file
     * @param \Graphox\Render\View $view
     */
    public function locateView(View $view)
    {
        $view->setFile(dirname(dirname(dirname(__DIR__))) . '/views/' . $view->getFile());
    }

    /**
     * Selects the view render from the view type
     * @param \Graphox\Render\View $view
     * @return \Graphox\Render\ViewRender
     * @throws \Exception
     */
    protected function getViewRender(View $view)
    {
        if (!isset($this->viewRenders[$view->getType()])) throw new \Exception('Invalid view type.');

        if (!is_object($this->viewRenders[$view->getType()]))
        {
            $class = $this->viewRenders[$view->getType()];
            $this->viewRenders[$view->getType()] = new $class($this);
        }

        return $this->viewRenders[$view->getType()];
    }

    /**
     * Renders a partial plus layout
     * @todo implement theming
     * @param \Graphox\Render\View $view
     * @return string
     */
    public function render(View $view)
    {
        return $this->renderPartial($view);
    }

    /**
     * Renders a partial
     * @param \Graphox\Render\View $view
     * @return string
     */
    public function renderPartial(View $view)
    {
        $this->locateView($view);
        $render = $this->getViewRender($view);


        return $render->render($view);
    }

}
