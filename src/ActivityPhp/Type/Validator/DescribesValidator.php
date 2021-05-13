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

use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Extended\Object\Profile;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\DescribesValidator is a dedicated
 * validator for describes attribute.
 */
class DescribesValidator implements ValidatorInterface
{
    /**
     * Validate an DESCRIBES attribute value
     *
     * @param object $value
     * @param mixed  $container A Profile type
     */
    public function validate($value, $container): bool
    {
        // Validate that container is a Tombstone type
        Util::subclassOf($container, Profile::class, true);

        if (is_object($value)) {
            // MUST be an Object
            return Util::subclassOf($value, ObjectType::class, true);
        }

        return false;
    }
}
