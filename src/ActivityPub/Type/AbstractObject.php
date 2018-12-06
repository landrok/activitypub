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
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    /**
     * Get a list of all attributes names
     * 
     * @return array
     */
    public function getAttributes()
    {
        return array_keys(
            get_object_vars($this)
        );
    }
}
