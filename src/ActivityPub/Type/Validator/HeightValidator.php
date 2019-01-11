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

use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Extended\Object\Image;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\HeightValidator is a dedicated
 * validator for height attribute.
 */
class HeightValidator implements ValidatorInterface
{
    /**
     * Validate height value
     * 
     * @param int    $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Link
        Util::subclassOf($container, [Link::class, Image::class], true);

        // Must be a non negative integer
        return Util::validateNonNegativeInteger($value);
    }
}
