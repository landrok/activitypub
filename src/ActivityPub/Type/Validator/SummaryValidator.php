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
 * \ActivityPub\Type\Validator\SummaryValidator is a dedicated
 * validator for summary attribute.
 */
class SummaryValidator implements ValidatorInterface
{
    /**
     * Validate a summary attribute value
     * 
     * @param null|string  $value
     * @param mixed        $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is an ObjectType type
        Util::subclassOf($container, ObjectType::class, true);

        // Must be a string
        if (is_null($value) || is_string($value)) {
            return true;
        }
    }
}
