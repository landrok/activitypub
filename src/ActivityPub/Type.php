<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub;

use ActivityPub\Type\TypeResolver;
use ActivityPub\Type\Validator;
use Exception;

/**
 * \ActivityPub\Type is a Factory for ActivityStreams 2.0 types.
 * 
 * It provides shortcuts methods for type instanciation and more.
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#types
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#activity-types
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#actor-types
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#object-types
 */ 
abstract class Type
{
    /**
     * Factory method to create type instance and set attributes values
     * 
     * To see which default types are defined and their attributes:
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#types
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#activity-types
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#actor-types
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#object-types
     * 
     * @param  string|array $type
     * @param  array        $attributes
     * @return mixed        \object
     */
    public static function create($type, array $attributes = [])
    {
        if (!is_string($type) && !is_array($type)) {
            throw new Exception(
                "Type parameter must be a string or an array"
            );
        }

        if (is_array($type)) {
            if (!isset($type['type'])) {
                throw new Exception(
                    "Type parameter must have a 'type' property"
                );
            } else {
                $attributes = $type;
            }
        }

        $class = is_array($type)
            ? TypeResolver::getClass($type['type'])
            : TypeResolver::getClass($type);

        if (is_string($class)) {
            $class = new $class();
        }

        foreach ($attributes as $name => $value) {
            $class->set($name, $value);
        }

        return $class;
    }

    /**
     * Add a custom type definition
     * It overrides defined types
     * 
     * @param string $name A short name.
     * @param string $class Fully qualified class name
     */
    public static function add($name, $class)
    {
        TypeResolver::addCustomType($name, $class);
    }

    /**
     * Add a custom validator for an attribute.
     * It checks that it implements Validator\Interface
     * 
     * @param string $name An attribute name to validate.
     * @param string $class A validator class name
     */
    public static function addValidator($name, $class)
    {
        Validator::add($name, $class);
    }
}
