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

use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;
use ActivityPub\Type\Validator\Traits\ListOrObjectTrait;

/**
 * \ActivityPub\Type\Validator\AudienceValidator is a dedicated
 * validator for audience attribute.
 */
class AudienceValidator implements ValidatorInterface
{
    use ListOrObjectTrait;

    /**
     * Validate an audience object
     * 
     * @param object $item
     */
    protected function validateObject($item)
    {
        if (!Util::hasProperties($item, ['type'])) {
            return false;
        }

        // Validate Link type
        if ($item->type == 'Link') {
            return Util::validateLink($item);
        }

        // Validate Object type
        return Util::hasProperties($item, ['name'])
            && is_string($item->name);
    }
}
