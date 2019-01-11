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
use ActivityPub\Type\ValidatorTools;

/**
 * \ActivityPub\Type\Validator\NameMapValidator is a dedicated
 * validator for nameMap attribute.
 */
class NameMapValidator extends ValidatorTools
{
    /**
     * Validate a nameMap value
     * 
     * @param string  $value
     * @param mixed   $container
     * @return bool
     */
    public function validate($value, $container)
    {
        return $this->validateMap('name', $value, $container);
    }
}
