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

use ActivityPhp\Type\Core\Link;
use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\PreviewValidator is a dedicated
 * validator for preview attribute.
 */
class PreviewValidator implements ValidatorInterface
{
    /**
     * Validate a preview value
     *
     * @param  string|array|object $value
     * @param  mixed  $container
     */
    public function validate($value, $container): bool
    {
        // Container is an ObjectType or a Link
        Util::subclassOf(
            $container,
            [ObjectType::class, Link::class],
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

        return false;
    }
}
