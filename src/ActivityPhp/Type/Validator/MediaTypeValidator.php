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

use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\MediaTypeValidator is a dedicated
 * validator for mediaType attribute.
 */
class MediaTypeValidator implements ValidatorInterface
{
    /**
     * Validate a mediaType attribute value
     * 
     * @param string  $value
     * @param mixed   $container
     * @return bool
     */
    public function validate($value, $container)
    {
        return Util::validateMediaType($value);
    }
}
