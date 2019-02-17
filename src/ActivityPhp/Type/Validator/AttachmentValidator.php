<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Validator;

use ActivityPhp\Type\ValidatorTools;

/**
 * \ActivityPhp\Type\Validator\AttachmentValidator is a dedicated
 * validator for attachment attribute.
 */
class AttachmentValidator extends ValidatorTools
{
    /**
     * Validate an attachment value
     * 
     * @param  array $value
     * @param  mixed  $container An Object type
     * @return bool
     */
    public function validate($value, $container)
    {
        if (!count($value)) {
            return true;
        }

        return $this->validateListOrObject(
            $value,
            $container,
            $this->getLinkOrUrlObjectValidator()
        );
    }
}
