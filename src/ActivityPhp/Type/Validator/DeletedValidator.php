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

use ActivityPhp\Type\Extended\Object\Tombstone;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\DeletedValidator is a dedicated
 * validator for deleted attribute.
 */
class DeletedValidator implements ValidatorInterface
{
    /**
     * Validate a DELETED attribute value
     *
     * @param string $value
     * @param mixed  $container A Tombstone type
     */
    public function validate($value, $container): bool
    {
        // Validate that container is a Tombstone type
        Util::subclassOf($container, Tombstone::class, true);

        if (is_string($value)) {
            // MUST be a datetime
            if (Util::validateDatetime($value)) {
                return true;
            }
        }

        return false;
    }
}
