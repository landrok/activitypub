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

use ActivityPub\Type\Extended\Object\Place;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\LongitudeValidator is a dedicated
 * validator for longitude attribute.
 */
class LongitudeValidator implements ValidatorInterface
{
    /**
     * Validate a latitude attribute value
     * 
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Place
        Util::subclassOf($container, Place::class, true);

        return Util::between($value, -180, 180);
    }
}
