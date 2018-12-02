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

use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Extended\Object\Profile;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\DescribesValidator is a dedicated
 * validator for describes attribute.
 */
class DescribesValidator implements ValidatorInterface
{
    /**
     * Validate an DESCRIBES attribute value
     * 
     * @param object $value
     * @param mixed  $container A Profile type
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Tombstone type
        Util::subclassOf($container, Profile::class, true);

        if (is_object($value)) {
            // MUST be an Object
            return Util::subclassOf($value, ObjectType::class, true);
        }
    }
}
