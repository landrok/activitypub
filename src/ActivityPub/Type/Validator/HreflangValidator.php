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

use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\HreflangValidator is a dedicated
 * validator for hreflang attribute.
 */
class HreflangValidator implements ValidatorInterface
{
    /**
     * Validate href value
     * 
     * @param string $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Link
        Util::subclassOf($container, Link::class, true);

        // Must be a valid URL
        return Util::validateBcp47($value);
    }
}
