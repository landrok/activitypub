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

use ActivityPub\Type\Extended\Object\Tombstone;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\DeletedValidator is a dedicated
 * validator for deleted attribute.
 */
class DeletedValidator implements ValidatorInterface
{
    /**
     * Validate an DELETED attribute value
     * 
     * @param string $value
     * @param mixed  $container A Tombstone type
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Tombstone type
        Util::subclassOf($container, Tombstone::class, true);

        if (is_string($value)) {
            // MUST be a datetime
            if (Util::validateDatetime($value)) {
                return true;
            }
        }
    }
}
