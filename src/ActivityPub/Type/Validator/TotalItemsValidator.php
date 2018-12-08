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

use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\TotalItemsValidator is a dedicated
 * validator for totalItems attribute.
 */
class TotalItemsValidator implements ValidatorInterface
{
    /**
     * Validate totalItems value
     * 
     * @param int    $value
     * @param mixed  $container A Collection
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container type is Collection
        Util::subclassOf($container, Collection::class, true);

        // Must be a non negative integer
        return Util::validateNonNegativeInteger($value);
    }    
}
