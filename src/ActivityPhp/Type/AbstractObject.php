<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type;

use ActivityPhp\Type;
use ActivityPhp\TypeFactory;
use DeepCopy\DeepCopy;
use Exception;

/**
 * \ActivityPhp\Type\ObjectAbstract is an abstract class for all
 * Activity Streams Core Types.
 * 
 * @see https://www.w3.org/TR/activitystreams-core/#model
 */ 
abstract class AbstractObject
{

    protected $_context;

    private $copier;

    public function __construct()
    {
        $this->copier = new DeepCopy();
    }

    /**
     * Standard setter method
     * - Perform content validation if a validator exists
     * 
     * @param  string $name
     * @param  mixed  $value
     * @return $this
     */
    public function set($name, $value)
    {
        if ($name !== '@context') {
            $this->has($name, true);
        } else {
            $this->_context = $value;
        }

        $this->$name = $value;

        return $this;
    }

    /**
     * Standard getter method
     * 
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        $this->has($name, true);
        return $this->$name;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->_context;
    }

    public function getProperties(): array
    {
        $properties = array_flip(array_keys(get_object_vars($this)));
        unset($properties['_context']);
        unset($properties['copier']);

        return array_keys($properties);
    }

    public function getPropertyValues(): array
    {
        $propertyValues = get_object_vars($this);

        unset($propertyValues['copier']);

        return $propertyValues;
    }

    /**
     * Checks that property exists
     * 
     * @param  string $name
     * @param  bool   $strict
     * @return bool
     */
    public function has($name, $strict = false)
    {
        if ('@context' === $name || property_exists($this, $name)) {
            return true;
        }

        if ($strict) {
            // Defined properties
            //$allowed = $this->typeFactory->create($this->type)->getProperties();
            //sort($allowed);

            throw new Exception(
                sprintf(
                    'Property "%s" is not defined. Type="%s", ' .
                    'Class="%s"' . PHP_EOL . 'Allowed properties: %s',
                    $name,
                    $this->get('type'),
                    get_class($this),
                    implode(', ', [])
                )
            );
        }

        return false;
    }

    /**
     * Get a copy of current object and return a new instance
     * 
     * @return self A new instance of this object
     */
    public function copy()
    {
        return $this->copier->copy($this);
    }

    /**
     * Magical isset method
     * 
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return property_exists($this, $name)
            || null !== $this->$name;
    }

    /**
     * Magical setter method
     * 
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Magical getter method
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}
