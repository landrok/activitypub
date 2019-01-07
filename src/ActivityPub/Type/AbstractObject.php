<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type;

use Exception;

/**
 * \ActivityPub\Type\ObjectAbstract is an abstract class for all
 * Activity Streams Core Types.
 * 
 * @see https://www.w3.org/TR/activitystreams-core/#model
 */ 
abstract class AbstractObject
{
    /**
     * Standard setter method
     * - Perform content validation if a validator exists
     * 
     * @param string $name
     * @param mixed  $value
     * @return $this
     */
    public function set($name, $value)
    {
        // Is there any validators
        if (!Validator::validate($name, $value, $this)) {
        	throw new Exception(
                "Rejected value. Attribute={$name}, value="
                . print_r($value, true)
        	);
        }

        $this->{$name} = $value;

        return $this;
    }

    /**
     * Standard getter method
     * 
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        $this->has($name, true);

        return $this->{$name};
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
        if (property_exists($this, $name)) {
            return true;
        }
        
        if ($strict) {
            throw new Exception(
                sprintf(
                    'Property "%s" is not defined for class "%s"',
                    $name,
                    self::class
                )
            );
        }
    }

    /**
     * Get a list of all properties names
     * 
     * @return array
     */
    public function getProperties()
    {
        return array_keys(
            get_object_vars($this)
        );
    }

    /**
     * Get a list of all properties and their values 
     * as an associative array.
     * Null values are not returned.
     * 
     * @return array
     */
    public function toArray()
    {
        return array_filter(
            get_object_vars($this),
            function($value) {
                return !is_null($value);
            }
        );
    }

    /**
     * Magical isset method
     * 
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return property_exists($this, $name);
    }

    /**
     * Magical setter method
     * 
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->has($name);
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

    /**
     * Overloading methods
     * 
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // Getters
        if (strpos($name, 'get') === 0) {
            $attr = lcfirst(substr($name, 3));
            return $this->get($attr);
        }

        // Setters
        if (strpos($name, 'set') === 0) {
            if (count($arguments) == 1) {
                $attr = lcfirst(substr($name, 3));
                return $this->set($attr, $arguments[0]);
            } else {
                throw new Exception(
                    sprintf(
                        'Expected exactly one argument for method "%s()"',
                        $name
                    )
                );
            }
        }

        throw new Exception(
            sprintf(
                'Method "%s" is not defined',
                $name
            )
        );
    }
}
