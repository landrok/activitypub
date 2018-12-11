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

use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\CurrentValidator is a dedicated
 * validator for current attribute.
 */
class CurrentValidator implements ValidatorInterface
{
    /**
     * Validate a current attribute value
     * 
     * @param string  $value
     * @param mixed   $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container must be a Collection
        Util::subclassOf($container, Collection::class, true);

        // URL
        if (Util::validateUrl($value)) {
            return true;
        }

        // Can be a JSON string
        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

        // Link or CollectionPage
        return Util::validateLink($value)
            || Util::validateCollectionPage($value);
    }
}
