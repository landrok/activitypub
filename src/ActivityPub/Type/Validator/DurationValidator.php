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
 * \ActivityPub\Type\Validator\DurationValidator is a dedicated
 * validator for duration attribute.
 */
class DurationValidator implements ValidatorInterface
{
    /**
     * Validate an DURATION attribute value
     * 
     * @param string $value
     * @param mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container has an ObjectType type
        Util::subclassOf($container, ObjectType::class, true);

        if (is_string($value)) {
            // MUST be a XML 8601 Duration formatted string
            return Util::isDuration($value, true);
        }
    }
}
