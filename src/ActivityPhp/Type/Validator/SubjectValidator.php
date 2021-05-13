<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Validator;

use ActivityPhp\Type\Extended\Object\Relationship;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\SubjectValidator is a dedicated
 * validator for subject attribute.
 */
class SubjectValidator implements ValidatorInterface
{
    /**
     * Validate subject value
     *
     * @param  string|array|object $value
     * @param  object              $container
     */
    public function validate($value, $container): bool
    {
        // Container is a Relationship
        Util::subclassOf(
            $container,
            Relationship::class,
            true
        );

        // URL
        if (is_string($value)) {
            return Util::validateUrl($value);
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // Link or ObjectType
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::isObjectType($value);
        }

        return false;
    }
}
