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

use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\AccuracyValidator is a dedicated
 * validator for accuracy attribute.
 */
class AccuracyValidator implements ValidatorInterface
{
    /**
     * Validate an ACCURACY attribute value
     *
     * @param mixed  $value
     * @param mixed  $container An object
     */
    public function validate($value, $container): bool
    {
        return is_numeric($value)
            && (float) $value >= 0
            && (float) $value <= 100.0;
    }
}
