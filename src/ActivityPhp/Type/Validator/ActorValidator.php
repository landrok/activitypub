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

use ActivityPhp\Type\Core\Collection;
use ActivityPhp\Type\Core\Link;
use ActivityPhp\Type\Extended\AbstractActor;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\ActorValidator is a dedicated
 * validator for actor attribute.
 */
class ActorValidator implements ValidatorInterface
{
    /**
     * Validate an ACTOR attribute value
     *
     * @param mixed  $value
     * @param mixed  $container An object
     */
    public function validate($value, $container): bool
    {
        // Can be an indirect link
        if (is_string($value) && Util::validateUrl($value)) {
            return true;
        }

        if (is_array($value)) {
            $value = Util::arrayToType($value);
        }

        // A collection
        if (is_array($value)) {
            return $this->validateObjectCollection($value);
        }

        // Must be an object
        if (! is_object($value)) {
            return false;
        }

        // A single actor
        return $this->validateObject($value);
    }

    /**
     * Validate an Actor object type
     *
     * @param object|array $item
     */
    protected function validateObject($item): bool
    {
        if (is_array($item)) {
            $item = Util::arrayToType($item);
        }

        Util::subclassOf(
            $item, [
                AbstractActor::class,
                Link::class,
                Collection::class
            ],
            true
        );

        return true;
    }

    /**
     * Validate a list of object
     * Collection can contain:
     * - Indirect URL
     * - An actor object
     */
    protected function validateObjectCollection(array $collection): bool
    {
        foreach ($collection as $item) {
            if (is_array($item) && $this->validateObject($item)) {
                continue;
            }
            if (is_object($item) && $this->validateObject($item)) {
                continue;
            }
            if (is_string($item) && Util::validateUrl($item)) {
                continue;
            }

            return false;
        }

        return count($collection) > 0;
    }
}
