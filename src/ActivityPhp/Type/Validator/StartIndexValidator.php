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

use ActivityPhp\Type\Core\OrderedCollectionPage;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\StartIndexValidator is a dedicated
 * validator for startIndex attribute.
 */
class StartIndexValidator implements ValidatorInterface
{
    /**
     * Validate startIndex value
     *
     * @param int    $value
     * @param mixed  $container An OrderedCollectionPage
     */
    public function validate($value, $container): bool
    {
        // Container type is OrderedCollectionPage
        Util::subclassOf($container, OrderedCollectionPage::class, true);

        // Must be a non negative integer
        return Util::validateNonNegativeInteger($value);
    }
}
