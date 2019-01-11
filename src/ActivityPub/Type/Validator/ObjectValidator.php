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

use ActivityPub\Type\Core\Activity;
use ActivityPub\Type\Extended\Object\Relationship;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\ObjectValidator is a dedicated
 * validator for object attribute.
 */
class ObjectValidator implements ValidatorInterface
{
    /**
     * Validate an object value
     * 
     * @param  object $value
     * @param  mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container is an ObjectType or a Link
        Util::subclassOf(
            $container, 
            [Activity::class, Relationship::class],
            true
        );

        // URL
        if (is_string($value)) {
            return Util::validateUrl($value);
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // Link or Object
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::isObjectType($value);
        }
        
        // Collection
        if (is_array($value)) {
            foreach ($value as $item) {
                if (is_string($item)) {
                    return Util::validateUrl($item);
                } elseif (is_array($item)) {
                    $item = Util::arrayToType($item);
                    Util::subclassOf($item, [ObjectType::class], true);
                } else {
                    return false;
                }
            }

            return count($value) && true;
        }
    }
}
