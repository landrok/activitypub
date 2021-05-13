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
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\GeneratorValidator is a dedicated
 * validator for generator attribute.
 */
class GeneratorValidator implements ValidatorInterface
{
    /**
     * Validate a generator attribute value
     *
     * @param string|object|array $value
     * @param object              $container
     */
    public function validate($value, $container): bool
    {
        // Validate that container has an ObjectType type
        Util::subclassOf($container, ObjectType::class, true);

        if (Util::validateUrl($value)) {
            return true;
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // MUST be a valid Actor type
        return Util::isActorType($value) || Util::validateLink($value);
    }
}
