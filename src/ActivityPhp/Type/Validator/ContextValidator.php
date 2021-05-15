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
 * \ActivityPhp\Type\Validator\ContextValidator is a dedicated
 * validator for context attribute.
 */
class ContextValidator implements ValidatorInterface
{
    /**
     * Validate a context attribute value
     *
     * @param string|array  $value
     * @param object        $container
     */
    public function validate($value, $container): bool
    {
        // URL
        if (Util::validateUrl($value)) {
            return true;
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // Link or Object
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::validateObject($value);
        }

        return false;
    }
}
