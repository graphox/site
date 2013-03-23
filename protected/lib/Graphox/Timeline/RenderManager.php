<?php

/**
 * Handles the rendering of the different types of verbs.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

/**
 * Handles the rendering of the different types of verbs.
 * @package Graphox\Timeline
 */
class RenderManager
{
    /**
     * The renderManager used to render views.
     * @var \Graphox\Render\RenderManager
     */
    private $renderManager;

    /**
     * The renders that are used for the verbs.
     * @todo use @verbRender
     * @var array
     */
    private $renders = array(
        'Graphox\Verb\Share' => 'Graphox\Verb\Render\Share',
    );

    /**
     * Set the renderManager to use for view render.
     * @param \Graphox\Render\RenderManager $renderManager
     */
    public function setRenderManager(\Graphox\Render\RenderManager $renderManager)
    {
        $this->renderManager = $renderManager;
    }

    /**
     * Returns the renderManager that is used to render views.
     * @return \Graphox\Render\RenderManager
     */
    public function getRenderManager()
    {
        return $this->renderManager;
    }

    /**
     * Initializes the class and sets the renderManager
     * @param \Graphox\Render\RenderManager $renderManager
     */
    public function __construct($renderManager)
    {
        $this->setRenderManager($renderManager);
    }

    /**
     * Lazy load the render class.
     * @param IVerb $verbClass
     * @return IVerbRender
     */
    public function getRender($verbClass)
    {
        //Locate parent clas, mostly used for proxies
        if (!isset($this->renders[$verbClass]))
        {
            do
            {
                $info = new \ReflectionClass($verbClass);
                while ($parent = $info->getParentClass())
                {

                    if (isset($this->renders[$parent->name]))
                    {
                        $class = $this->renders[$parent->name];
                        $obj = new $class;
                        $this->renders[$class] = & $obj;
                        $this->renders[$verbClass] = & $obj;
                        break 2;
                    }
                };

                throw new Exception('Could not find render.');
            }
            while (false);
        }
        elseif (!is_object($this->renders[$verbClass]))
        {
            $class = $this->renders[$verbClass];
            $this->renders[$verbClass] = new $class;
        }

        return $this->renders[$verbClass];
    }

    /**
     * Renders an action to a string.
     * @param \Graphox\Timeline\IAction $action
     * @return string
     */
    public function renderAction(IAction $action)
    {
        $verb = $action->getVerb();
        $render = $this->getRender(get_class($verb));



        return $this->getRenderManager()->render(
                        new \Graphox\Render\View('timeline/action',
                        array(
                    'title' => $render->renderTitle($verb),
                    'body' => $render->renderBody($verb)
                        ))
        );
    }

    /**
     * Render all actions from an iterator into a string.
     * @param \Graphox\Timeline\TimelineIterator $iterator
     * @return string
     */
    public function renderTimeline(TimelineIterator $iterator)
    {
        $s = '';
        foreach ($iterator as $action)
        {
            $s .= $this->renderAction($action);
        }

        return $s;
    }

}

