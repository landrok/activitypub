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
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\OriginValidator is a dedicated
 * validator for origin attribute.
 */
class OriginValidator implements ValidatorInterface
{
    /**
     * Validate an origin value
     * 
     * @param  object $value
     * @param  mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container is an Activity
        Util::subclassOf(
            $container, 
            [Activity::class],
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
    }
}
