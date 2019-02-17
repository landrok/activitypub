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

use ActivityPhp\Type\Core\Link;
use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorTools;

/**
 * \ActivityPhp\Type\Validator\TypeValidator is a dedicated
 * validator for type attribute.
 */
class TypeValidator extends ValidatorTools
{
    /**
     * Validate a type value
     * 
     * @param  string $value
     * @param  mixed  $container An Object type
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is an ObjectType or a Link
        Util::subclassOf(
            $container, 
            [ObjectType::class, Link::class],
            true
        );

        return $this->validateString(
            $value
        );
    }
}
