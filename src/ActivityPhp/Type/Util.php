<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type;

use ActivityPhp\Type;
use DateInterval;
use DateTime;
use Exception;

/**
 * \ActivityPhp\Type\Util is an abstract class for
 * supporting validators checks & transformations.
 */
abstract class Util
{
    /**
     * Allowed units
     * 
     * @var string[]
     */
    protected static $units = [
        'cm', 'feet', 'inches', 'km', 'm', 'miles'
    ];

    /**
     * Tranform an array into an ActivityStreams type
     * 
     * @param  array $item
     * @return mixed An ActivityStreams type or given array if type key 
     * is not defined.
     */
    public static function arrayToType(array $item)
    {
        // May be an array representing an AS object
        // It must have a type key
        if (isset($item['type'])) {
            return Type::create($item['type'], $item);
        }

        return $item;
    }

    /**
     * Validate an URL
     * 
     * @param  mixed $value
     * @return bool
     */
    public static function validateUrl($value)
    {
        return is_string($value)
            && filter_var($value, FILTER_VALIDATE_URL) !== false
            && in_array(
                parse_url($value, PHP_URL_SCHEME),
                ['http', 'https', 'magnet']
            );
    }

    /**
     * Validate a magnet link
     * 
     * @todo Make a better validation as xs is not the only parameter
     * @see  https://en.wikipedia.org/wiki/Magnet_URI_scheme
     * 
     * @param  mixed $value
     * @return bool
     */
    public static function validateMagnet($value)
    {
        return is_string($value)
            && strlen($value) < 262144
            && preg_match(
                '#^magnet:\?xs=(https?)://.*$#iu', 
                urldecode($value)
        
        );
    }

    /**
     * Validate an OStatus tag string
     * 
     * @param  mixed $value
     * @return bool
     */
    public static function validateOstatusTag($value)
    {
        return is_string($value)
            && strlen($value) < 262144
            && preg_match(
                '#^tag:([\w\-\.]+),([\d]{4}-[\d]{2}-[\d]{2}):([\w])+Id=([\d]+):objectType=([\w]+)#iu', 
                $value
            );
    }

    /**
     * Validate a rel attribute value.
     * 
     * @see https://tools.ietf.org/html/rfc5988
     * 
     * @param  string $value
     * @return bool
     */
    public static function validateRel($value)
    {
        return is_string($value)
            && preg_match("/^[^\s\r\n\,]+\z/i", $value);
    }

    /**
     * Validate a non negative integer.
     * 
     * @param  int $value
     * @return bool
     */
    public static function validateNonNegativeInteger($value)
    {
        return is_int($value)
            && $value >= 0;
    }

    /**
     * Validate a non negative number.
     * 
     * @param  int|float $value
     * @return bool
     */
    public static function validateNonNegativeNumber($value)
    {
        return is_numeric($value)
            && $value >= 0;
    }

    /**
     * Validate units format.
     * 
     * @param  string $value
     * @return bool
     */
    public static function validateUnits($value)
    {
        if (is_string($value)) {
            if (in_array($value, self::$units)
                || self::validateUrl($value)
            ) {
                return true;
            }
        }
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
     * @param  string $value
     * @return array
     * @throws \Exception if JSON decoding process has failed
     */
    public static function decodeJson(string $value)
    {
        $json = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decoding failed for string: ' . $value);
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
    public static function hasProperties($item, array $properties, $strict = false)
    {
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
     * Validate a reference with a Link or an Object with an URL
     * 
     * @param object $item
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
     * @param array|object $item
     * @return bool
     */
    public static function validateLink($item)
    {
        if (is_array($item)) {
            $item = (object)$item;
        }

        if (!is_object($item)) {
            return false;
        }

        self::hasProperties($item, ['type'], true);

        // Validate Link type
        if ($item->type != 'Link') {
            return false;
        }

        // Validate Object type
        self::hasProperties($item, ['href'], true);

        return self::validateUrl($item->href)
            || self::validateMagnet($item->href);
    }

    /**
     * Validate a datetime
     * 
     * @param  string $value
     * @return bool
     */
    public static function validateDatetime($value)
    {
        if (!is_string($value)
            || !preg_match(
            '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(.*)$/',
            $value
        )) {
            return false;
        }

        try {
            $dt = new DateTime($value);
            return true;
        } catch(Exception $e) {
        }
    }

    /**
     * Check that container class is a subclass of a given class
     * 
     * @param object $container
     * @param string|array $classes
     * @param bool   $strict If true, throws an exception
     * @return bool
     * @throws \Exception
     */
    public static function subclassOf($container, $classes, $strict = false)
    {
        if (!is_array($classes)) {
            $classes = [$classes];
        }

        foreach ($classes as $class) {
            if (get_class($container) == $class
                || is_subclass_of($container, $class)
            ) {
                return true;
            }
        }

        if ($strict) {
            throw new Exception(
                sprintf(
                    'Class "%s" MUST be a subclass of "%s"',
                    get_class($container),
                    implode(', ', $classes)
                )
            );
        }
    }

    /**
     * Checks that a numeric value is part of a range.
     * If a minimal value is null, value has to be inferior to max value
     * If a maximum value is null, value has to be superior to min value
     * 
     * @param int|float $value
     * @param null|int|float $min
     * @param null|int|float $max
     * @return bool
     */
    public static function between($value, $min, $max)
    {
        if (!is_numeric($value)) {
            return false;
        }

        switch (true) {
            case is_null($min) && is_null($max):
                return false;
            case is_null($min):
                return $value <= $max;
            case is_null($max):
                return $value >= $min;
            default:
                return $value >= $min
                    && $value <= $max;
        }
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
    }

    /**
     * Checks that it's an object type
     * 
     * @param  object $item
     * @return bool
     */
    public static function isObjectType($item)
    {
        return TypeResolver::isScope($item);
    }

    /**
     * Checks that it's an actor type
     * 
     * @param  object $item
     * @return bool
     */
    public static function isActorType($item)
    {
        return TypeResolver::isScope($item, 'actor');
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
     * Validate a BCP 47 language value
     * 
     * @param  string $value
     * @return bool
     */
    public static function validateBcp47($value)
    {
        return is_string($value)
            && preg_match(
                '/^(((en-GB-oed|i-ami|i-bnn|i-default|i-enochian|i-hak|i-klingon|i-lux|i-mingo|i-navajo|i-pwn|i-tao|i-tay|i-tsu|sgn-BE-FR|sgn-BE-NL|sgn-CH-DE)|(art-lojban|cel-gaulish|no-bok|no-nyn|zh-guoyu|zh-hakka|zh-min|zh-min-nan|zh-xiang))|((([A-Za-z]{2,3}(-([A-Za-z]{3}(-[A-Za-z]{3}){0,2}))?)|[A-Za-z]{4}|[A-Za-z]{5,8})(-([A-Za-z]{4}))?(-([A-Za-z]{2}|[0-9]{3}))?(-([A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3}))*(-([0-9A-WY-Za-wy-z](-[A-Za-z0-9]{2,8})+))*(-(x(-[A-Za-z0-9]{1,8})+))?)|(x(-[A-Za-z0-9]{1,8})+))$/',
                $value
        );
    }

    /**
     * Validate a plain text value
     * 
     * @param  string $value
     * @return bool
     */
    public static function validatePlainText($value)
    {
        return is_string($value)
            && preg_match(
                '/^([^<]+)$/',
                $value
        );
    }

    /**
     * Validate mediaType format
     * 
     * @param  string $value
     * @return bool
     */
    public static function validateMediaType($value)
    {
        return is_string($value)
            && preg_match(
                '#^(([\w]+[\w\-]+[\w+])/(([\w]+[\w\-\.\+]+[\w]+)|(\*));?)+$#',
                $value
        );
    }

    /**
     * Validate a Collection type
     * 
     * @param object $item
     * @return bool
     */
    public static function validateCollection($item)
    {
        if (!is_object($item)) {
            return false;
        }

        self::hasProperties(
            $item,
            ['totalItems', 'current', 'first', 'last', 'items'],
            true
        );

        return true;
    }

    /**
     * Validate a CollectionPage type
     * 
     * @param object $item
     * @return bool
     */
    public static function validateCollectionPage($item)
    {
        // Must be a Collection
        if (!self::validateCollection($item)) {
            return false;
        }

        self::hasProperties(
            $item,
            ['partOf', 'next', 'prev'],
            true
        );

        return true;
    }
}
