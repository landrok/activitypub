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
 * \ActivityPub\Type\Validator\IdValidator is a dedicated
 * validator for id attribute.
 */
class IdValidator implements ValidatorInterface
{
	/**
	 * Validate an ID attribute value
	 * 
	 * @param mixed  $value
	 * @param mixed  $container An object
	 * @return bool
	 */
    public function validate($value, $container)
    {
	return filter_var($value, FILTER_VALIDATE_URL);
    }
}
