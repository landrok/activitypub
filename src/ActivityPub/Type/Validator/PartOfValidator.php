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


use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Core\OrderedCollection;
use ActivityPub\Type\Extended\AbstractActor;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\PartOfValidator is a dedicated
 * validator for partOf attribute.
 */
class PartOfValidator implements ValidatorInterface
{
    /**
     * Validate a partOf value
     * 
     * @param  object $value
     * @param  mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container is CollectionPage or OrderedCollectionPage type
        if (!Util::subclassOf($container, CollectionPage::class)
            && !Util::subclassOf($container, OrderedCollectionPage::class)
        ) {
            return false;
        }

        // URL
        if (is_string($value)) {
            return Util::validateUrl($value)
                || Util::validateLink($value);
        }

        // Link or Collection
        if (is_object($value)) {
            return Util::validateLink($value)
                || Util::validateCollection($value);
        }
    }
}
