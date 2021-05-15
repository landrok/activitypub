<?php

declare(strict_types=1);

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
 * \ActivityPhp\Type\Validator\PartOfValidator is a dedicated
 * validator for partOf attribute.
 */
class PartOfValidator implements ValidatorInterface
{
    /**
     * Validate a partOf value
     *
     * @param  string|array|object $value
     * @param  object              $container
     */
    public function validate($value, $container): bool
    {
        // Container is CollectionPage or OrderedCollectionPage type
        Util::subclassOf(
            $container, [
                CollectionPage::class, OrderedCollectionPage::class
            ], true
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
                || Util::validateCollection($value);
        }

        return false;
    }
}
