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
 * \ActivityPhp\Type\Validator\EndTimeValidator is a dedicated
 * validator for endTime attribute.
 */
class EndTimeValidator implements ValidatorInterface
{
    /**
     * Validate an ENDTIME attribute value
     * 
     * @param string $value
     * @param mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container has an ObjectType type
        Util::subclassOf($container, ObjectType::class, true);

        // MUST be a valid xsd:dateTime
        return Util::validateDatetime($value);
    }
}
