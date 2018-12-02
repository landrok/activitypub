<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Validator\Traits;

use ActivityPub\Type\Util;

/**
 * This trait provides a classic workflow of validation.
 * First it checks if a Link or an Object is given
 * Then it checks if href attribute (for Link) or name attribute
 * (Object) are given.
 * It validates that URL is valid for href attribute.
 */
trait ValidateLinkOrNamedObject
{
    /**
     * Validate a reference with a Link with a valid href
     * or an Object with a name attribute
     * 
     * @param object $value
     * @return bool
     */
    protected function validateObject($item)
    {
        if (!Util::hasProperties($item, ['type'])) {
            return false;
        }

        // Validate Link type
        if ($item->type == 'Link') {
            return Util::validateLink($item);
        }

        // Validate Object type
        return Util::hasProperties($item, ['name'])
            && is_string($item->name);	
    }
}
