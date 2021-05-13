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

use ActivityPhp\Type\Extended\Object\Place;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\LatitudeValidator is a dedicated
 * validator for latitude attribute.
 */
class LatitudeValidator implements ValidatorInterface
{
    /**
     * Validate a latitude attribute value
     *
     * @param mixed  $value
     * @param mixed  $container An object
     */
    public function validate($value, $container): bool
    {
        // Validate that container is a Place
        Util::subclassOf($container, Place::class, true);

        return Util::between($value, -90, 90);
    }
}
