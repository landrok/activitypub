<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp;

use ActivityPhp\Type\AbstractObject;
use ActivityPhp\Type\Dialect;
use ActivityPhp\Type\TypeResolver;
use ActivityPhp\Type\Validator;
use Exception;

/**
 * \ActivityPhp\Type is a Factory for ActivityStreams 2.0 types.
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
     * @return \ActivityPhp\Type\AbstractObject
     */
    public static function create($type, array $attributes = [])
    {
        if (!is_string($type) && !is_array($type)) {
            throw new Exception(
                "Type parameter must be a string or an array. Given="
                . gettype($type)
            );
        }

        if (is_array($type)) {
            if (!isset($type['type'])) {
                throw new Exception(
                    "Type parameter must have a 'type' key"
                );
            } else {
                $attributes = $type;
            }
        }

        try {

            $class = is_array($type)
                ? TypeResolver::getClass($type['type'])
                : TypeResolver::getClass($type);

        } catch(Exception $e) {
            $message = json_encode($attributes, JSON_PRETTY_PRINT);
            throw new Exception(
                $e->getMessage() . "\n$message"
            );
        }

        if (is_string($class)) {
            $class = new $class();
        }

        self::extend($class);

        foreach ($attributes as $name => $value) {
            $class->set($name, $value);
        }

        return $class;
    }

    /**
     * Create an activitystream type from a JSON string
     */
    public static function fromJson(string $json): AbstractObject
    {
        $data = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE
            && is_array($data)
        ) {
            return self::create($data);
        }

        throw new Exception(
            sprintf(
                "An error occurred during the JSON decoding.\n '%s'",
                $json
            )
        );
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

    /**
     * ActivityPub real world applications not only implements the basic
     * vocabulary.
     * They extends basic protocol with custom properties.
     * These extensions are called dialects.
     * 
     * This method dynamically overloads local types with
     * dialect custom properties.
     * 
     * @param \ActivityPhp\Type\AbstractObject $type
     */
    private static function extend(AbstractObject $type)
    {
        // @todo should call Dialect stack to see if there are any 
        // properties to overloads $type with
        Dialect::extend($type);
    }
}
