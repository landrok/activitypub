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
 * \ActivityPhp\Type\Validator\UnitsValidator is a dedicated
 * validator for units attribute.
 */
class UnitsValidator implements ValidatorInterface
{
    /**
     * Validate units value
     *
     * @param string $value
     * @param mixed  $container A Place
     */
    public function validate($value, $container): bool
    {
        // Container must be Place
        Util::subclassOf($container, Place::class, true);

        // Must be a valid units
        return Util::validateUnits($value);
    }
}
