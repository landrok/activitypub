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
use ActivityPub\Type\Validator\Traits\ListOrObjectTrait;
use ActivityPub\Type\Validator\Traits\ValidateLinkOrUrlObject;

/**
 * \ActivityPub\Type\Validator\BtoValidator is a dedicated
 * validator for bto attribute.
 */
class BtoValidator implements ValidatorInterface
{
    /**
     * Browse a list of object or one single object
     */
    use ListOrObjectTrait;
    
    /**
     * Validate an Object
     */
    use ValidateLinkOrUrlObject;
}
