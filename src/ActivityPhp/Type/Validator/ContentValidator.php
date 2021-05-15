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
 * \ActivityPhp\Type\Validator\ContentValidator is a dedicated
 * validator for content attribute.
 */
class ContentValidator implements ValidatorInterface
{
    /**
     * Validate a content attribute value
     *
     * @param string|null  $value
     * @param mixed        $container
     */
    public function validate($value, $container): bool
    {
        // Must be a string or null
        if (is_null($value) || is_string($value)) {
            return true;
        }

        return false;
    }
}
