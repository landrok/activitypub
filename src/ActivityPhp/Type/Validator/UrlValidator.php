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

use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorInterface;

/**
 * \ActivityPhp\Type\Validator\UrlValidator is a dedicated
 * validator for url attribute.
 */
class UrlValidator implements ValidatorInterface
{
    /**
     * Validate url value
     *
     * @param  string|array $value
     * @param  mixed  $container A Link
     */
    public function validate($value, $container): bool
    {
        // Validate that container is an ObjectType
        Util::subclassOf($container, ObjectType::class, true);

        // Must be a valid URL
        if (is_array($value) && is_int(key($value))) {

            foreach ($value as $key => $item) {
                if (! $this->validateUrlOrLink($item)) {
                    return false;
                }
            }

            return true;
        }

        return $this->validateUrlOrLink($value);
    }

    /**
     * Validate that a value is a Link or an URL
     *
     * @param  string|\ActivityPhp\Type\Core\Link $value
     */
    protected function validateUrlOrLink($value): bool
    {
        return Util::validateUrl($value)
            || Util::validateLink($value)
            || Util::validateMagnet($value);
    }
}
