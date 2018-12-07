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

use ActivityPub\Type\Extended\AbstractActor;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\PreferredUsernameValidator is a dedicated
 * validator for preferredUsername attribute.
 */
class PreferredUsernameValidator implements ValidatorInterface
{
    /**
     * Validate preferredUsername value
     * 
     * @param string $value
     * @param mixed  $container An Actor
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is an Actor
        Util::subclassOf($container, AbstractActor::class, true);

        // Must be a valid string
        return is_string($value);
    }
}
