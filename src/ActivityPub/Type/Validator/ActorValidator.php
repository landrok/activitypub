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

use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\ActorValidator is a dedicated
 * validator for actor attribute.
 */
class ActorValidator implements ValidatorInterface
{
    /**
     * Validate an ACTOR attribute value
     * 
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
	// Can be an indirect link
	if (is_string($value) && $this->validateUrl($value)) {
	    return true;
	}
	
	// Can be a JSON string
	if (is_string($value)) {
	    $value = json_decode($value);
	    
	    if (json_last_error() !== JSON_ERROR_NONE) {
		return false;
	    }
	}

	// A collection
	if (is_array($value)) {
	    return $this->validateObjectCollection($value);
	}

	// Must be an object
	if (!is_object($value)) {
	    return false;
	}

	// A single actor
	return $this->validateObject($value);
    }

    /**
     * Validate an Actor object type
     * 
     * @param object $value
     */
    protected function validateObject($value)
    {
	if (!property_exists($value, 'id')) {
	    return false;
	}

	return $this->validateUrl($value->id);
    }

    /**
     * Validate an URL
     * 
     * @param string $value
     */
    protected function validateUrl($value)
    {
	return filter_var($value, FILTER_VALIDATE_URL);
    }

    /**
     * Validate a list of object
     * Collection can contain:
     * - Indirect URL
     * - An actor object
     * 
     * @param array $collection
     */
    protected function validateObjectCollection(array $collection)
    {
	foreach ($collection as $item) {
	    if (is_object($item) && $this->validateObject($item)) {
		continue;
	    } elseif (is_string($item) && $this->validateUrl($item)) {
		continue;
	    }

	    return false;
	}

	return true;
    }
}
