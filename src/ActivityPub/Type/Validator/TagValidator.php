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
 * \ActivityPub\Type\Validator\TagValidator is a dedicated
 * validator for tag attribute.
 */
class TagValidator extends ValidatorTools
{
    /**
     * Validate a tag value
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

        return $this->validateObjectCollection(
            $value,
            $this->getCollectionItemsValidator()
        );
    }
}
