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
     * @return bool
     */
    public static function hasProperties($item, $properties)
    {
        foreach ($properties as $property) {
            if (!property_exists($item, $property)) {
                return false;
            }
        }

        return true;
    }
}
