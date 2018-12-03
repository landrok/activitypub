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

use DateInterval;
use DateTime;
use Exception;

/**
 * \ActivityPub\Type\Util is an abstract class for
 * supporting validators checks & transformations.
 */
abstract class Util
{
    /**
     * Validate an URL
     * 
     * @param  string $value
     * @return bool
     */
    public static function validateUrl($value)
    {
		return is_string($value)
            && filter_var($value, FILTER_VALIDATE_URL);
    }

    /**
     * Validate an Object type
     * 
     * @param  object $item
     * @return bool
     */
    public static function validateObject($item)
    {
        return self::hasProperties($item, ['type'])
            && is_string($item->type)
            && $item->type == 'Object';
    }

    /**
     * Decode a JSON string
     * 
     * @param string $value
     * @return array|object
     * @throws \Exception if JSON decoding process has failed
     */
    public static function decodeJson($value)
    {
        $json = json_decode($value);

	    if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(
                'JSON decoding failed for string: ' . $value
            );
	    }

        return $json;
    }

    /**
     * Checks that all properties exist for a stdClass
     * 
     * @param  object $item
     * @param  array  $properties
     * @param  bool   $strict If true throws an \Exception,
     *                        otherwise returns false
     * @return bool
     * @throws \Exception if a property is not set
     */
    public static function hasProperties(
        $item, 
        array $properties,
        $strict = false
    ) {
        foreach ($properties as $property) {
            if (!property_exists($item, $property)) {
                if ($strict) {
                    throw new Exception(
                        sprintf(
                            'Attribute "%s" MUST be set for item: %s',
                            $property,
                            print_r($item, true)
                        )
                    );
                }

                return false;
            }
        }

        return true;
    }

    /**
     * Validate an object type with type attribute
     * 
     * @param object $item
     * @param string $type An expected type
     * @return bool
     */
    public static function isType($item, $type)
    {
        // Validate that container is a certain type
        if (!is_object($item)) {
            return false;
        }
        
        if (property_exists($item, 'type')
            && is_string($item->type)
            && $item->type == $type
        ) {
            return true;
        }
    }


    /**
     * Validate a reference with a Link or an Object with an URL
     * 
     * @param object $value
     * @return bool
     */
    public static function isLinkOrUrlObject($item)
    {
        self::hasProperties($item, ['type'], true);

        // Validate Link type
        if ($item->type == 'Link') {
            return self::validateLink($item);
        }

        // Validate Object type
        self::hasProperties($item, ['url'], true);

        return self::validateUrl($item->url);
    }

    /**
     * Validate a reference as Link
     * 
     * @param object
     * @return bool
     */
    public static function validateLink($item)
    {
        self::hasProperties($item, ['type'], true);

        // Validate Link type
        if ($item->type != 'Link') {
            return false;
        }

        // Validate Object type
        self::hasProperties($item, ['href'], true);

        return self::validateUrl($item->href);
    }

    /**
     * Validate a datetime
     * 
     * @param  string $value
     * @return bool
     */
    public static function validateDatetime($value)
    {
        if (!preg_match(
            '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/',
            $value
        )) {
            return false;
        }

        try {
            $dt = new DateTime($value);
            return true;
        } catch(Exception $e) {
        }

        return false;
    }

    /**
     * Check that container class is a subclass of a given class
     * 
     * @param object $container
     * @param string $class
     * @param bool   $strict If true, throws an exception
     * @return bool
     * @throws \Exception
     */
    public static function subclassOf($container, $class, $strict = false)
    {
        if (get_class($container) == $class
            || is_subclass_of($container, $class)
        ) {
            return true;
        }

        if ($strict) {
            throw new Exception(
                sprintf(
                    'Class "%s" MUST be a subclass of "%s"',
                    get_class($container),
                    $class
                )
            );
        }

        return false;
    }

    /**
     * Check that a given string is a valid XML Schema xsd:duration
     * 
     * @param string $duration
     * @param bool   $strict If true, throws an exception
     * @return bool
     * @throws \Exception
     */
    public static function isDuration($duration, $strict = false)
    {
		try {
			new DateInterval($duration);

			return true;
		} catch(\Exception $e) {
		}

        if ($strict) {
            throw new Exception(
                sprintf(
                    'Duration "%s" MUST respect xsd:duration',
                    $duration
                )
            );
        }

        return false;
    }
}
