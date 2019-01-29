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

use ActivityPub\Type\Util;
use ActivityPub\Type\Validator;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\ContextValidator is a dedicated
 * validator for context attribute.
 */
class ContextValidator implements ValidatorInterface
{
    /**
     * Validate a context attribute value
     * 
     * @param string|array  $value
     * @param object        $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // URL
        if (Util::validateUrl($value)) {
            return true;
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // Link or Object
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::validateObject($value);
        }
    }
}
