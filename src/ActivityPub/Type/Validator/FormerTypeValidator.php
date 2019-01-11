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
 * \ActivityPub\Type\Validator\FormerTypeValidator is a dedicated
 * validator for formerType attribute.
 */
class FormerTypeValidator implements ValidatorInterface
{
    /**
     * Validate a formerType attribute value
     * 
     * @param string|object $value
     * @param mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container has an Tombstone type
        Util::subclassOf($container, Tombstone::class, true);

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // MUST be a valid Object type
        return Util::isObjectType($value);
    }    
}
