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

use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\IconValidator is a dedicated
 * validator for icon attribute.
 */
class IconValidator implements ValidatorInterface
{
    /**
     * Validate icon item
     *
     * @param string $item
     * @param mixed  $container An object
     * @todo Support Image objects and Link objects
     * @todo Implement size checks
     */
    public function validate($item, $container): bool
    {
        // Validate that container is a ObjectType
        Util::subclassOf($container, ObjectType::class, true);

        if (is_string($item)) {
            return Util::validateUrl($item);
        }

        if (is_array($item)) {
            $item = Util::arrayToType($item);
        }

        if (is_array($item)) {
            foreach ($item as $value) {

                if (is_array($value)) {
                    $value = Util::arrayToType($value);
                }

                if (is_string($value) && Util::validateUrl($value)) {
                    continue;
                }

                if (!$this->validateObject($value)) {
                    return false;
                }
            }

            return true;
        }

        // Must be an Image or a Link
        return $this->validateObject($item);
    }

    /**
     * Validate an object format
     *
     * @param object $item
     * @return bool
     */
    protected function validateObject($item)
    {
        return Util::validateLink($item)
            || Util::isType($item, 'Image');
    }
}
