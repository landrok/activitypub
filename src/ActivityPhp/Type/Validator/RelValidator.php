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
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\RelValidator is a dedicated
 * validator for rel attribute.
 */
class RelValidator implements ValidatorInterface
{
    /**
     * Validate rel value
     *
     * @param string|array $value
     * @param mixed  $container A Link
     */
    public function validate($value, $container): bool
    {
        // Validate that container is a Link
        Util::subclassOf($container, Link::class, true);

        // Must be a valid Rel
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                if (!is_int($key)
                    || !Util::validateRel($item)) {
                    return false;
                }
            }

            return true;
        }

        return Util::validateRel($value);
    }
}
