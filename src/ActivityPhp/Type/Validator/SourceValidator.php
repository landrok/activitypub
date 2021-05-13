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

use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\SourceValidator is a dedicated
 * validator for source attribute.
 */
class SourceValidator implements ValidatorInterface
{
    /**
     * Validate source value
     *
     * @param  array|object $value
     * @param  object       $container
     */
    public function validate($value, $container): bool
    {
        // Container is an ObjectType
        Util::subclassOf(
            $container,
            ObjectType::class,
            true
        );

        if (is_array($value)) {
            $value = (object)$value;
        }

        if (is_object($value)) {
            return Util::hasProperties(
                $value,
                ['content', 'mediaType'],
                true
            );
        }

        return false;
    }
}
