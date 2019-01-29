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

use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\RepliesValidator is a dedicated
 * validator for replies attribute.
 */
class RepliesValidator implements ValidatorInterface
{
    /**
     * Validate replies value
     * 
     * @param  string|array|object $value
     * @param  object              $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container is an ObjectType
        Util::subclassOf(
            $container, 
            ObjectType::class,
            true
        );

        // URL
        if (is_string($value)) {
            return Util::validateUrl($value);
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // Link or Collection
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::validateCollection($value);
        }
    }
}
