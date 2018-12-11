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

use ActivityPub\Type\ValidatorTools;

/**
 * \ActivityPub\Type\Validator\AudienceValidator is a dedicated
 * validator for audience attribute.
 */
class AudienceValidator extends ValidatorTools
{
    /**
     * Validate an audience value
     * 
     * @param  string $value
     * @param  mixed  $container An Object type
     * @return bool
     */
    public function validate($value, $container)
    {
        return $this->validateListOrObject(
            $value,
            $container,
            $this->getLinkOrNamedObjectValidator()
        );
    }
}
