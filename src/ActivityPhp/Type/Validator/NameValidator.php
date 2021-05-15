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

use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\NameValidator is a dedicated
 * validator for name attribute.
 */
class NameValidator implements ValidatorInterface
{
    /**
     * Validate a name attribute value
     *
     * @param string|null $value
     * @param mixed $container
     */
    public function validate($value, $container): bool
    {
        if (is_null($value) || $value === '') {
            return true;
        }

        return Util::validatePlainText($value);
    }
}
