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

use ActivityPub\Type\Core\CollectionPage;
use ActivityPub\Type\Core\OrderedCollectionPage;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\NextValidator is a dedicated
 * validator for next attribute.
 */
class NextValidator implements ValidatorInterface
{
    /**
     * Validate a next value
     * 
     * @param  object $value
     * @param  mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container is CollectionPage or OrderedCollectionPage type
        Util::subclassOf(
            $container,
            [CollectionPage::class, OrderedCollectionPage::class],
            true
        );

        // URL
        if (is_string($value)) {
            return Util::validateUrl($value);
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // Link or Collection
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::validateCollectionPage($value);
        }
    }
}
