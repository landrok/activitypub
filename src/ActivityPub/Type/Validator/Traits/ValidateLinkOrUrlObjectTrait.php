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
 * Then it checks if href attribute (for Link) or url attribute
 * (Object) are given.
 * It validates that URL are correct.
 */
trait ValidateLinkOrUrlObjectTrait
{
    /**
     * Validate a reference with a Link or an Object with an URL
     * 
     * @param object $value
     * @return bool
     */
    protected function validateObject($item)
    {
        return Util::isLinkOrUrlObject($item);
    }
}
