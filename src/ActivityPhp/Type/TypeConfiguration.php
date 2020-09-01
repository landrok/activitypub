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

use Exception;

/**
 * \ActivityPhp\Type\TypeConfiguration is an abstract class for
 * types configuration.
 */
abstract class TypeConfiguration
{
    /**
     * Defaults
     * 
     * @var array
     */
    protected static $configs = [
        // Which behavior for undefined properties
        'undefined_properties' => 'strict',
    ];

    /**
     * Allowed configs
     * When a key is defined here, the property must be in defined values
     * 
     * @var array
     */
    protected static $allowed = [
        'undefined_properties' => [
           'strict',    // Throw an exception when a property is not defined
           'ignore',    // Ignore key and value
           'include',   // Set key and value
        ]
    ];

    /**
     * Set a custom configuration.
     * 
     * @param  string $name.
     * @param  mixed  $value.
     * @throws \Exception if $value is not allowed
     */
    public static function set(string $name, $value)
    {
        if (isset(self::$allowed[$name]) 
            && !in_array($value, self::$allowed[$name])
        ) {
            throw new Exception(
                sprintf(
                    'Configuration "%s" does not accept %s. Allowed: ',
                    $name,
                    $value,
                    implode(', ', self::$allowed[$name])
                )
            );
        }

        self::$configs[$name] = $value;
    }

    /**
     * Get a type configuration.
     * 
     * @param  string $name
     * @return mixed
     */
    public static function get($name)
    {
        return isset(self::$configs[$name])
            ? self::$configs[$name]
            : null;
    }
}
