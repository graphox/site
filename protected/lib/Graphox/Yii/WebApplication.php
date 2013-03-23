<?php
/**
 * Pimple support
 * @package Graphox\Yii
 * @author killme
 */

namespace Graphox\Yii;

use \Yii;

/**
 * Custom yii CWebApplication to support Pimple
 *
 */
class WebApplication extends \CWebApplication
{
    /**
     * @access private
     * @var Container the instance of the container
     */
    private $container;

    /**
     * Returns the container instance.
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the container from either an instance or an array containing the settings.
     * @param mixed $container
     * @throws \InvalidArgumentException
     */
    public function setContainer($container)
    {
        if (is_array($container))
        {
            $class = isset($container['class']) ? $container['class'] : '\Graphox\Yii\Container';
            $services = $container;
            unset($services['class']);

            //Recursive call
            $this->setContainer(new $class($services));
        }
        elseif ($container instanceof Container)
        {
            $this->container = $container;
        }
        else
        {
            throw new \InvalidArgumentException('Invalid container.');
        }
    }

}

