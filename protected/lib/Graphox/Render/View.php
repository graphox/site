<?php

/**
 * A view that can be rendered using the view render that handles the specified type.
 * @package Graphox\Render
 * @author killme
 */

namespace Graphox\Render;

/**
 * A view that can be rendered using the view render that handles the specified type.
 * @package Graphox\Render
 */
class View
{

    const TYPE_PHP = 'php';

    /**
     * The variables passed to the view.
     * @var array
     */
    private $vars;

    /**
     * The file to render.
     * @var string
     */
    private $file;

    /**
     * The type of the view, what viewrender to use.
     * @var string
     */
    private $type;

    /**
     * Initializes the class and sets these variables.
     * @param string $file
     * @param array $vars
     * @param string $type
     */
    public function __construct($file, $vars = array(
        ), $type = self::TYPE_PHP)
    {
        $this->file = $file;
        $this->vars = $vars;
        $this->type = $type;
    }

    /**
     * Returns the variables that are defined.
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Returns the file of the view.
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the type of the view
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the view file.
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

}

