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
 * \ActivityPhp\Type\Validator\HrefValidator is a dedicated
 * validator for href attribute.
 */
class HrefValidator implements ValidatorInterface
{
    /**
     * Validate href value
     *
     * @param string $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container): bool
    {
        // Validate that container is a Link or an Object
        Util::subclassOf(
            $container,
            [Link::class, ObjectType::class],
            true
        );

        // Must be a valid URL or a valid magnet link
        return Util::validateUrl($value)
            || Util::validateMagnet($value);
    }
}
