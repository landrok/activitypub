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
use ActivityPub\Type\Core\OrderedCollection;
use ActivityPub\Type\Extended\AbstractActor;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\FollowersValidator is a dedicated
 * validator for followers attribute.
 */
class FollowersValidator implements ValidatorInterface
{
    /**
     * Validate a FOLLOWERS attribute value
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

        if (is_string($value)) {
            return Util::validateUrl($value);
        }

        // A collection
        return is_object($value)
            ? $this->validateObject($value)
            : false;
    }

    /**
     * Validate that it is an OrderedCollection or a Collection
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
                Collection::class
        );
    }
}
