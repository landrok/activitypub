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

use ActivityPhp\Type\Core\Collection;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\CurrentValidator is a dedicated
 * validator for current attribute.
 */
class CurrentValidator implements ValidatorInterface
{
    /**
     * Validate a current attribute value
     *
     * @param string  $value
     * @param mixed   $container
     */
    public function validate($value, $container): bool
    {
        // Container must be a Collection
        Util::subclassOf($container, Collection::class, true);

        // URL
        if (Util::validateUrl($value)) {
            return true;
        }

        // Link or CollectionPage
        return Util::validateLink($value)
            || Util::validateCollectionPage($value);
    }
}
