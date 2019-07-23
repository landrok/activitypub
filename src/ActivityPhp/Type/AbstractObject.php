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
use Exception;

/**
 * \ActivityPhp\Type\ObjectAbstract is an abstract class for all
 * Activity Streams Core Types.
 * 
 * @see https://www.w3.org/TR/activitystreams-core/#model
 */ 
abstract class AbstractObject
{
    /**
     * Keep all properties values that have been set
     * 
     * @var array
     */
    private $_props = [];

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
        }

        // Validate given value
        if (!Validator::validate($name, $value, $this)) {
            $message = "Rejected value. Type='%s', Property='%s', value='%s'";
        	throw new Exception(
                sprintf(
                    $message,
                    get_class($this),
                    $name,
                    print_r($value, true)
                )
                . PHP_EOL
        	);
        }

        if ($name == '@context') {
            $this->_props[$name] = $value;
        }

        $this->_props[$name] = $this->transform($value);

        return $this;
    }

    /**
     * Affect a value to a property or an extended property
     * 
     * @param  mixed $value
     * @return mixed
     */
    private function transform($value)
    {
        // Deep typing
        if (is_array($value)) {
            if (isset($value['type'])) {
                return Type::create($value);
            } elseif (is_int(key($value))) {
                return array_map(function($value) {
                        return is_array($value) && isset($value['type'])
                            ? Type::create($value)
                            : $value;
                    },
                    $value
                );
            // Empty array, array that should not be casted as ActivityStreams types
            } else {
                return $value;
            }
        // Scalars 
        } else {
            return $value;
        }      
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
        return $this->_props[$name];
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
            if (!array_key_exists($name, $this->_props)) {
                $this->_props[$name] = $this->$name;
            }

            return true;
        }

        if (array_key_exists($name, $this->_props)) {
            return true;
        }

        if ($strict) {
            // Defined properties
            $allowed = Type::create($this->type)->getProperties();
            sort($allowed);

            throw new Exception(
                sprintf(
                    'Property "%s" is not defined. Type="%s", ' .
                    'Class="%s"' . PHP_EOL . 'Allowed properties: %s',
                    $name,
                    $this->get('type'),
                    get_class($this),
                    implode(', ', $allowed)
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
        return array_values(
            array_unique(
                array_merge(
                    array_keys($this->_props),
                    array_keys(
                        array_diff_key(
                            get_object_vars($this),
                            ['_props' => '1']
                        )
                    )
                )
            )
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
        $keys = array_keys( array_filter(
            get_object_vars($this),
            function($value, $key) {
                return !is_null($value) && $key != '_props';
            },
            ARRAY_FILTER_USE_BOTH
        ));

        $stack = [];

        // native properties
        foreach ($keys as $key) {
            if ($this->$key instanceof self) {
                $stack[$key] = $this->$key->toArray();
            } elseif (!is_array($this->$key)) {
                $stack[$key] = $this->$key;
            } elseif (is_array($this->$key)) {
                if (is_int(key($this->$key))) {
                    $stack[$key] = array_map(function($value) {
                            return $value instanceof self
                                ? $value->toArray()
                                : $value;
                        },
                        $this->$key
                    );
                } else {
                    $stack[$key] = $this->$key;
                }
            }
        }
        
        // _props
        foreach ($this->_props as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            if ($value instanceof self) {
                $stack[$key] = $value->toArray();
            } elseif (!is_array($value)) {
                $stack[$key] = $value;
            } elseif (is_array($value)) {
                if (is_int(key($value))) {
                    $stack[$key] = array_map(function($value) {
                            return $value instanceof self
                                ? $value->toArray()
                                : $value;
                        },
                        $value
                    );
                } else {
                    $stack[$key] = $value;
                }
            }
        }

        return $stack;
    }

    /**
     * Get a copy of current object and return a new instance
     * 
     * @return self A new instance of this object
     */
    public function copy()
    {
        return Type::create(
            $this->type,
            $this->toArray()
        );
    }

    /**
     * Extend current type properties
     * 
     * @param string $property
     * @param mixed  $default
     */
    public function extend(string $property, mixed $default = null)
    {
        if ($this->has($property)) {
            return;
        }

        if (!isset($this->_props[$property])
            || !array_key_exists($property, $this->props)
        ) {
            $this->_props[$property] = $default;
        }
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
            || array_key_exists($name, $this->props);
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
