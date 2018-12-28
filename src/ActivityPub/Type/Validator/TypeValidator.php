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

use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorTools;

/**
 * \ActivityPub\Type\Validator\TypeValidator is a dedicated
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
