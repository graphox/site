<?php

/**
 * Pimple support
 * @package Graphox\Yii
 * @author killme
 */

namespace Graphox\Yii;

use \Yii;

/**
 * Dependency container for yii.
 * @see \Pimple
 */
class Container extends \Pimple
{

    /**
     * @param string $id the id that may be changed when it is a yii core
     * @return boolean if the specified id is a yii core component
     */
    protected function isYiiCore(&$id)
    {
        if (strpos($id, 'yii.core.') !== FALSE)
        {
            $id = str_replace('yii.core.', '', $id);
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($id, $value)
    {
        if ($this->isYiiCore($id))
        {
            Yii::app()->{$id} = $value;
        }
        else return parent::offsetSet($id, $value);
    }
	/**
     * @see offsetSet
     */
    public function set($id, $value)
    {
        return $this->offsetSet($id, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($id)
    {
        if ($this->isYiiCore($id))
        {
            return Yii::app()->{$id};
        }
        else
        {
            return parent::offsetGet($id);
        }
    }
	/**
     * @see offsetGet
     */
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($id)
    {
        if ($this->isYiiCore($id))
        {
            return isset(Yii::app()->{$id});
        }
        else
        {
            parent::offsetExists($id);
        }
    }
	/**
     * {@inheritdoc}
     */
    public function offsetUnset($id)
    {
        if ($this->isYiiCore($id))
        {
            unset(Yii::app()->{$id});
        }
        else
        {
            parent::offsetUnset($id);
        }
    }
	/**
     * {@inheritdoc}
     */
    public function raw($id)
    {
        if ($this->isYiiCore($id))
        {
            if (!isset(Yii::app()->{$id})) throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.',
                        $id));
            else
            {
                return Yii::app()->{$id};
            }
        }

        return parent::raw($id);
    }
	/**
     * {@inheritdoc}
     */
    public function keys()
    {
        return array_merge(parent::keys(), Yii::app()->getComponents());
    }

}

