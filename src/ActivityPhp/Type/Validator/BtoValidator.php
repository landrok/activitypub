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

use ActivityPhp\Type\ValidatorTools;

/**
 * \ActivityPhp\Type\Validator\BtoValidator is a dedicated
 * validator for bto attribute.
 */
class BtoValidator extends ValidatorTools
{
    /**
     * Validate a bto value
     * 
     * @param  string $value
     * @param  mixed  $container An Object type
     * @return bool
     */
    public function validate($value, $container)
    {
        return $this->validateListOrObject(
            $value,
            $container,
            $this->getLinkOrUrlObjectValidator()
        );
    }
}
