<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type;

/**
 * \ActivityPub\Type\ValidatorTools is an abstract class for
 * attribute validation.
 * Its purpose is to be extended by ActivityPub\Type\Validator\*
 * classes.
 * It provides some methods to make some regular validations.
 * It implements \ActivityPub\Type\ValidatorInterface.
 */
abstract class ValidatorTools implements ValidatorInterface
{
    /**
     * Validate a map attribute value.
     * 
     * @param  string $type An attribute name
     * @param  object $map
     * @param  object $container A valid container
     * @return bool
     */
    protected function validateMap($type, $map, $container)
    {
        // A map
        if (!is_object($map)) {
            return false;
        }

        foreach ($map as $key => $value) {
            if (!Util::validateBcp47($key) 
                || !Validator::validate($type, $value, $container)
            ) {
                return false;
            }
        }

        return true;
    }
}
