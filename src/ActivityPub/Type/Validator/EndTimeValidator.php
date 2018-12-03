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
 * \ActivityPub\Type\Validator\EndTimeValidator is a dedicated
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
