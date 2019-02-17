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

use ActivityPhp\Type\Extended\AbstractActor;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorTools;

/**
 * \ActivityPhp\Type\Validator\PreferredUsernameValidator is a dedicated
 * validator for preferredUsername attribute.
 */
class PreferredUsernameValidator extends ValidatorTools
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

        return $this->validateString(
            $value
        );
    }
}
