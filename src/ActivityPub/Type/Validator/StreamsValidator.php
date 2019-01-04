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
 * \ActivityPub\Type\Validator\StreamsValidator is a dedicated
 * validator for streams attribute.
 */
class StreamsValidator implements ValidatorInterface
{
    /**
     * Validate streams value
     * 
     * @param int    $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is an Actor
        Util::subclassOf($container, AbstractActor::class, true);

        // Must be an array
        // @todo Better validation should be done
        return is_array($value);
    }
}
