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
use ActivityPhp\Type\Extended\Object\Image;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\HeightValidator is a dedicated
 * validator for height attribute.
 */
class HeightValidator implements ValidatorInterface
{
    /**
     * Validate height value
     *
     * @param int    $value
     * @param mixed  $container An object
     */
    public function validate($value, $container): bool
    {
        // Validate that container is a Link
        Util::subclassOf($container, [Link::class, Image::class], true);

        // Must be a non negative integer
        return Util::validateNonNegativeInteger($value);
    }
}
