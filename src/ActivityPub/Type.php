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

use ActivityPub\Type\Util;

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
     * @param  string $type
     * @param  array  $attributes
     * @return mixed  \object
     */
    public static function create(string $type, array $attributes = [])
    {
        $class = Util::getClass($type);

        $instance = new $class();

        foreach ($attributes as $name => $value) {
            $instance->set($name, $value);
        }

        return $instance;
    }
}
