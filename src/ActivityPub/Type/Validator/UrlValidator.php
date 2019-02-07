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

use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\UrlValidator is a dedicated
 * validator for url attribute.
 */
class UrlValidator implements ValidatorInterface
{
    /**
     * Validate url value
     * 
     * @param  string|array $value
     * @param  mixed  $container A Link
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is an ObjectType
        Util::subclassOf($container, ObjectType::class, true);

        // Must be a valid URL
        if (is_array($value) && is_int(key($value))) {

            foreach ($value as $key => $item) {
                if (!$this->validateUrlOrLink($item)) {
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
     * @param  string|\ActivityPub\Type\Core\Link $value
     * @return bool
     */
    protected function validateUrlOrLink($value)
    {
        return Util::validateUrl($value)
            || Util::validateLink($value)
            || Util::validateMagnet($value);
    }
}
