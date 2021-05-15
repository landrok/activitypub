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

use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\SummaryValidator is a dedicated
 * validator for summary attribute.
 */
class SummaryValidator implements ValidatorInterface
{
    /**
     * Validate a summary attribute value
     *
     * @param string|null  $value
     * @param mixed        $container
     */
    public function validate($value, $container): bool
    {
        // Validate that container is an ObjectType type
        Util::subclassOf($container, ObjectType::class, true);

        // Must be a string
        return is_null($value) || is_string($value);
    }
}
