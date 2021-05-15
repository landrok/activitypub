<?php

declare(strict_types=1);

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
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\StreamsValidator is a dedicated
 * validator for streams attribute.
 */
class StreamsValidator implements ValidatorInterface
{
    /**
     * Validate streams value
     *
     * @param array  $value
     * @param object $container An object
     */
    public function validate($value, $container): bool
    {
        // Validate that container is an Actor
        Util::subclassOf($container, AbstractActor::class, true);

        // Must be an array
        // @todo Better validation should be done
        return is_array($value);
    }
}
