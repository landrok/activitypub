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

use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\AltitudeValidator is a dedicated
 * validator for altitude attribute.
 */
class AltitudeValidator implements ValidatorInterface
{
    /**
     * Validate an ALTITUDE attribute value
     *
     * @param mixed  $value
     * @param mixed  $container An object
     */
    public function validate($value, $container): bool
    {
        if (is_float($value) || is_int($value)) {
            return true;
        }

        return false;
    }
}
