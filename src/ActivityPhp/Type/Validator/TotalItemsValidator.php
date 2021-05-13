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
 * \ActivityPhp\Type\Validator\TotalItemsValidator is a dedicated
 * validator for totalItems attribute.
 */
class TotalItemsValidator implements ValidatorInterface
{
    /**
     * Validate totalItems value
     *
     * @param int    $value
     * @param mixed  $container A Collection
     */
    public function validate($value, $container): bool
    {
        // Container type is Collection
        Util::subclassOf($container, Collection::class, true);

        // Must be a non negative integer
        return Util::validateNonNegativeInteger($value);
    }
}
