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
use ActivityPub\Type\Validator\Traits\ValidateLinkOrNamedObject;

/**
 * \ActivityPub\Type\Validator\AudienceValidator is a dedicated
 * validator for audience attribute.
 */
class AudienceValidator implements ValidatorInterface
{
    /**
     * Browse a list of object or one single object
     */
    use ListOrObjectTrait;

    /**
     * Validate an Object
     */
    use ValidateLinkOrNamedObject;
}
