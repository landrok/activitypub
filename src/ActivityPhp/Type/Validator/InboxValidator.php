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

use ActivityPhp\Type\Core\OrderedCollection;
use ActivityPhp\Type\Core\OrderedCollectionPage;
use ActivityPhp\Type\Extended\AbstractActor;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\InboxValidator is a dedicated
 * validator for inbox attribute.
 */
class InboxValidator implements ValidatorInterface
{
    /**
     * Validate a inbox attribute value
     *
     * @param string|object $value
     * @param mixed  $container
     * @todo Support indirect reference for followers attribute?
     */
    public function validate($value, $container): bool
    {
        // Validate that container is an AbstractActor type
        Util::subclassOf($container, AbstractActor::class, true);

        if (is_string($value)) {
            return Util::validateUrl($value);
        }

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
