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

use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\SourceValidator is a dedicated
 * validator for source attribute.
 */
class SourceValidator implements ValidatorInterface
{
    /**
     * Validate source value
     * 
     * @param  object $value
     * @param  mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container is an ObjectType
        Util::subclassOf(
            $container, 
            ObjectType::class,
            true
        );

        if (is_array($value)) {
            $value = json_encode($value);
        }

        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

        if (is_object($value)) {
            return Util::hasProperties(
                $value,
                ['content', 'mediaType'],
                true
            );
        }
    }
}
