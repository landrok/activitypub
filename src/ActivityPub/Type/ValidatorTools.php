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

use ActivityPub\Type\Core\ObjectType;
use Exception;

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
     * @param  mixed  $map
     * @param  object $container A valid container
     * @return bool
     */
    protected function validateMap($type, $map, $container)
    {
        // A map
        if (!is_array($map)) {
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

    /**
     * Validate an attribute value
     * 
     * @param  mixed $value
     * @param  mixed $container An object
     * @param  callable $callback A dedicated validator
     * @return bool
     */
    public function validateListOrObject($value, $container, callable $callback)
    {
        Util::subclassOf($container, ObjectType::class, true);

        // Not supported: Can be a JSON string
        // Must be a value like an URL, a text
        if (is_string($value)) {
            return $callback($value);
        }

        if (is_array($value)) {
            // A collection
            if (is_int(key($value))) {
                return $this->validateObjectCollection($value, $callback);
            }
            
            $value = Util::arrayToType($value);
        }

        if (!is_object($value)) {
            return false;
        }

        return $callback($value);
    }

    /**
     * Validate a list of Collection
     * 
     * @param  array $collection
     * @param  callable $callback A dedicated validator
     * @return bool
     */
    protected function validateObjectCollection(array $collection, callable $callback)
    {
        foreach ($collection as $item) {
            if ($callback($item)) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * Validate that a value is a string
     *
     * @param  string $value
     * @return bool
     */
    protected function validateString($value)
    {
        if (!is_string($value) || strlen($value) < 1) {
            throw new Exception(
                sprintf(
                    'Value must be a non-empty string. Given: "%s"',
                    print_r($value, true)
                )
            );
        }

        return true;
    }

    /**
     * A callback function for validateListOrObject method
     * 
     * It validate a Link or a named object
     * 
     * @return callable
     */
    protected function getLinkOrNamedObjectValidator()
    {
        return function ($item) {
            if (is_string($item)) {
                return Util::validateUrl($item);
            }

            if (is_array($item)) {
                $item = Util::arrayToType($item);
            }

            if (is_object($item)) {
                Util::hasProperties($item, ['type'], true);

                // Validate Link type
                if ($item->type == 'Link') {
                    return Util::validateLink($item);
                }

                // Validate Object type
                Util::hasProperties($item, ['name'], true);

                return is_string($item->name);
            }
        };
    }

    /**
     * A callback function for validateListOrObject method
     *
     * Validate a reference with a Link or an Object with an URL
     *
     * @return callable
     */
    protected function getLinkOrUrlObjectValidator()
    {
        return function ($item) {
            if (is_array($item)) {
                $item = Util::arrayToType($item);
            }

            if (is_object($item) 
                && Util::isLinkOrUrlObject($item)) {
                return true;
            } elseif (Util::validateUrl($item)) {
                return true;
            }
        };
    }

    /**
     * Validate that a Question answer is a Note
     *
     * @return callable
     */
    protected function getQuestionAnswerValidator()
    {
        return function ($item) {
            if (is_array($item)) {
                $item = Util::arrayToType($item);
            }

            if (!is_object($item)) {
                return false;
            }

            Util::hasProperties($item, ['type', 'name'], true);
            return $item->type == 'Note'
                && is_scalar($item->name);
        };
    }

    /**
     * Validate that a list of items is valid
     *
     * @return callable
     */
    protected function getCollectionItemsValidator()
    {
        return function ($item) {
            if (is_array($item)) {
                $item = Util::arrayToType($item);
            }
            
            if (!is_object($item)) {
                return false;
            }
            
            return Util::hasProperties($item, ['type'], true);
        };
    }
}
