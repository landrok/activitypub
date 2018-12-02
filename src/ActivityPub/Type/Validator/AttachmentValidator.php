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
use ActivityPub\Type\Validator\Traits\ValidateLinkOrUrlObjectTrait;

/**
 * \ActivityPub\Type\Validator\AttachmentValidator is a dedicated
 * validator for attachment attribute.
 */
class AttachmentValidator implements ValidatorInterface
{
    /**
     * Browse a list of object or one single object
     */
    use ListOrObjectTrait;
    
    /**
     * Validate an Object
     */
    use ValidateLinkOrUrlObjectTrait;
}
