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
 * \ActivityPub\Type\Validator\UnitsValidator is a dedicated
 * validator for units attribute.
 */
class UnitsValidator implements ValidatorInterface
{
    /**
     * Validate units value
     * 
     * @param string $value
     * @param mixed  $container A Place
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container must be Place
        Util::subclassOf($container, Place::class, true);

        // Must be a valid units
        return Util::validateUnits($value);
    }
}
