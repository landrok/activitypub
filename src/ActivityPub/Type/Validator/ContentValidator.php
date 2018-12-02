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

use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\ContentValidator is a dedicated
 * validator for content attribute.
 */
class ContentValidator implements ValidatorInterface
{
    /**
     * Validate an content attribute value
     * 
     * @param string  $value
     * @param mixed   $container A Question type
     * @return bool
     */
    public function validate($value, $container)
    {
        // Must be a string
        if (is_string($value)) {
            return true;
        }
    }
}
