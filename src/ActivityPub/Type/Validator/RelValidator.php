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
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\RelValidator is a dedicated
 * validator for rel attribute.
 */
class RelValidator implements ValidatorInterface
{
    /**
     * Validate rel value
     * 
     * @param string|array $value
     * @param mixed  $container A Link
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Link
        Util::subclassOf($container, Link::class, true);

        // Must be a valid Rel
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                if (!is_int($key
                ) || !Util::validateRel($item)) {
                    return false;
                }
            }
            
            return true;
        }

        return Util::validateRel($value);
    }
}
