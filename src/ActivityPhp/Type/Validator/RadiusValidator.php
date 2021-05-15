<?php

declare(strict_types=1);

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
 * \ActivityPhp\Type\Validator\RadiusValidator is a dedicated
 * validator for radius attribute.
 */
class RadiusValidator implements ValidatorInterface
{
    /**
     * Validate radius value
     *
     * @param mixed $value
     * @param mixed $container A Place
     */
    public function validate($value, $container): bool
    {
        // Container must be Place
        Util::subclassOf($container, Place::class, true);

        // Must be a valid radius
        return Util::validateNonNegativeNumber($value);
    }
}
