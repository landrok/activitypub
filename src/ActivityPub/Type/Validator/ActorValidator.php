<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Validator;

use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Extended\AbstractActor;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\ActorValidator is a dedicated
 * validator for actor attribute.
 */
class ActorValidator implements ValidatorInterface
{
    /**
     * Validate an ACTOR attribute value
     * 
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        // Can be an indirect link
        if (is_string($value) && Util::validateUrl($value)) {
            return true;
        }
        
        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // A collection
        if (is_array($value)) {
            return $this->validateObjectCollection($value);
        }

        // Must be an object
        if (!is_object($value)) {
            return false;
        }

        // A single actor
        return $this->validateObject($value);
    }

    /**
     * Validate an Actor object type
     * 
     * @param object|array $item
     * @return bool
     */
    protected function validateObject($item)
    {
        if (is_array($item)) {
            $item = Util::arrayToType($item);
        }

        Util::subclassOf(
            $item, [
                AbstractActor::class,
                Link::class,
                Collection::class
            ],
            true
        );

        return true;
    }

    /**
     * Validate a list of object
     * Collection can contain:
     * - Indirect URL
     * - An actor object
     * 
     * @param array $collection
     * @return bool
     */
    protected function validateObjectCollection(array $collection)
    {
        foreach ($collection as $item) {
            if (is_array($item) && $this->validateObject($item)) {
                continue;
            } elseif (is_object($item) && $this->validateObject($item)) {
                continue;
            } elseif (is_string($item) && Util::validateUrl($item)) {
                continue;
            }

            return false;
        }

        return count($collection) && true;
    }
}
