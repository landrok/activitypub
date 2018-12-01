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
 * \ActivityPub\Type\Validator\AttributedToValidator is a dedicated
 * validator for attributedTo attribute.
 */
class AttributedToValidator implements ValidatorInterface
{
    /**
     * Validate an ATTRIBUTEDTO attribute value
     * 
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a ObjectType type
        if (!is_object($container)
            || !($container instanceof ObjectType)
        ) {
            return false;
        }

        // Can be a JSON string
        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

        // A collection
        if (is_array($value)) {
            return $this->validateObjectCollection($value);
        }

        if (!is_object($value)) {
            return false;
        }

        return $this->validateObject($value);
    }

    /**
     * Validate an attachment
     * 
     * @param string|object $item
     */
    protected function validateObject($item)
    {
        if (!Util::hasProperties($item, ['type'])) {
            return false;
        }

        // Validate Link type
        if ($item->type == 'Link') {
            return Util::validateLink($item);
        }

        // Validate Object type
        return Util::hasProperties($item, ['name'])
            && is_string($item->name);	
    }

    /**
     * Validate a list of object
     * Collection MUST contain objects with following attributes:
     * - a Note type
     * - a name attribute
     * 
     * @param array $collection
     */
    protected function validateObjectCollection(array $collection)
    {
        foreach ($collection as $item) {
            if (is_object($item) && $this->validateObject($item)) {
                continue;
            } elseif (Util::validateUrl($item)) {
                continue;
            }

            return false;
        }

        return true;
    }
}
