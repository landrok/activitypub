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
use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorTools;

/**
 * \ActivityPub\Type\Validator\ItemsValidator is a dedicated
 * validator for items attribute.
 */
class ItemsValidator extends ValidatorTools
{
    /**
     * Validate items value
     * 
     * @param  string $value
     * @param  mixed  $container A Collection type
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is a Collection
        Util::subclassOf(
            $container, 
            [Collection::class],
            true
        );

        // URL type
        if (is_string($value)) { 
            return Util::validateUrl($value);
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // Link type
        if (is_object($value)) { 
            return Util::subclassOf($value, Link::class, true);
        }

        // A Collection
        if (!is_array($value)) {
            return false;
        }

        if (!count($value)) {
            return false;
        }

        return $this->validateObjectCollection(
            $value,
            $this->getCollectionItemsValidator()
        );
    }
}
