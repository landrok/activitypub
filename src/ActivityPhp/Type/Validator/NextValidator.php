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

use ActivityPhp\Type\Core\CollectionPage;
use ActivityPhp\Type\Core\OrderedCollectionPage;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\NextValidator is a dedicated
 * validator for next attribute.
 */
class NextValidator implements ValidatorInterface
{
    /**
     * Validate a next value
     * 
     * @param  string|array|object $value
     * @param  object              $container
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
