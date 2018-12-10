<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Validator\Traits;

use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Util;

/**
 * This trait provides a classic workflow of validation.
 * First it checks that parent container is an ObjectType.
 * Then it tries to decode a JSON value if a string is provided
 * It switches between a list or an object validation
 */
trait ListOrObjectTrait
{
    /**
     * Validate an attribute value
     * 
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        Util::subclassOf($container, ObjectType::class, true);

        // Can be a JSON string
        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

        // A collection
        if (is_array($value)) {
            return $this->validateObjectCollection($value);
        }

        if (!is_object($value)) {
            return false;
        }

        return $this->validateObject($value);
    }

    /**
     * Validate a list of object
     * 
     * @param array $collection
     * @return bool
     */
    protected function validateObjectCollection(array $collection)
    {
        foreach ($collection as $item) {
            if (is_object($item) && $this->validateObject($item)) {
                continue;
            } elseif (Util::validateUrl($item)) {
                continue;
            }

            return false;
        }

        return true;
    }
}
