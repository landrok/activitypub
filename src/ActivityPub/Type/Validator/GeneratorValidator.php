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
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\GeneratorValidator is a dedicated
 * validator for generator attribute.
 */
class GeneratorValidator implements ValidatorInterface
{
    /**
     * Validate a generator attribute value
     * 
     * @param string|object $value
     * @param mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container has an ObjectType type
        Util::subclassOf($container, ObjectType::class, true);

        if (Util::validateUrl($value)) {
            return true;
        }

        // MUST be a valid Actor type
        return Util::isActorType($value) || Util::validateLink($value);
    }    
}
