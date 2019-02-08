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
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\NameValidator is a dedicated
 * validator for name attribute.
 */
class NameValidator implements ValidatorInterface
{
    /**
     * Validate a name attribute value
     * 
     * @param null|string  $value
     * @param mixed   $container
     * @return bool
     */
    public function validate($value, $container)
    {
        if (is_null($value)) {
            return true;
        }

        return Util::validatePlainText($value);
    }
}
