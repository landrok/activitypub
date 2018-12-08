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
 * \ActivityPub\Type\Validator\ContentMapValidator is a dedicated
 * validator for contentMap attribute.
 */
class ContentMapValidator implements ValidatorInterface
{
    /**
     * Validate a contentMap value
     * 
     * @param string  $value
     * @param mixed   $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Can be a JSON string
        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

        return Util::validateMap('content', $value, $container);
    }
}
