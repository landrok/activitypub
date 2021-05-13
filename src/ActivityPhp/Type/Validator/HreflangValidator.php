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

use ActivityPhp\Type\Core\Link;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\HreflangValidator is a dedicated
 * validator for hreflang attribute.
 */
class HreflangValidator implements ValidatorInterface
{
    /**
     * Validate href value
     *
     * @param string $value
     * @param mixed  $container An object
     */
    public function validate($value, $container): bool
    {
        // Validate that container is a Link
        Util::subclassOf($container, Link::class, true);

        // Must be a valid URL
        return Util::validateBcp47($value);
    }
}
