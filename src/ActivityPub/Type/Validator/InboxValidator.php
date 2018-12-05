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

use ActivityPub\Type\Core\OrderedCollection;
use ActivityPub\Type\Core\OrderedCollectionPage;
use ActivityPub\Type\Extended\AbstractActor;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\InboxValidator is a dedicated
 * validator for inbox attribute.
 */
class InboxValidator implements ValidatorInterface
{
    /**
     * Validate a inbox attribute value
     * 
     * @param object $value
     * @param mixed  $container
     * @return bool
     * @todo Support indirect reference for followers attribute?
     */
    public function validate($value, $container)
    {
        // Validate that container is an AbstractActor type
        Util::subclassOf($container, AbstractActor::class, true);

        // An OrderedCollection
        return is_object($value)
            ? $this->validateObject($value)
            : false;
    }

    /**
     * Validate that it is an OrderedCollection
     * 
     * @param object $collection
     * @return bool
     */
    protected function validateObject($collection)
    {
        return Util::subclassOf(
            $collection,
            OrderedCollection::class
        ) || Util::subclassOf(
            $collection,
            OrderedCollectionPage::class
        );
    }
}
