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

use ActivityPub\Type\Core\OrderedCollectionPage;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\StartIndexValidator is a dedicated
 * validator for startIndex attribute.
 */
class StartIndexValidator implements ValidatorInterface
{
    /**
     * Validate startIndex value
     * 
     * @param int    $value
     * @param mixed  $container An OrderedCollectionPage
     * @return bool
     */
    public function validate($value, $container)
    {
        // Container type is OrderedCollectionPage
        Util::subclassOf($container, OrderedCollectionPage::class, true);

        // Must be a non negative integer
        return Util::validateNonNegativeInteger($value);
    }    
}
