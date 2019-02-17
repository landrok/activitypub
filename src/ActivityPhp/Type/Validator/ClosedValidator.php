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

use ActivityPhp\Type\Extended\Activity\Question;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\ClosedValidator is a dedicated
 * validator for closed attribute.
 */
class ClosedValidator implements ValidatorInterface
{
    /**
     * Validate an CLOSED attribute value
     * 
     * @param bool|string|object|array  $value
     * @param object $container A Question type
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Question type
        Util::subclassOf($container, Question::class, true);

        // Can be a boolean
        if (is_bool($value)) {
            return true;
        }
        
        if (is_string($value)) {
            // Can be a datetime
            if (Util::validateDatetime($value)) {
                return true;
            }
            
            // Can be an URL
            if (Util::validateUrl($value)) {
                return true;
            }
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // An Object or a Link
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::validateObject($value);
        }
    }
}
