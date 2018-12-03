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

use ActivityPub\Type\Extended\AbstractActor;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorInterface;

/**
 * \ActivityPub\Type\Validator\EndpointsValidator is a dedicated
 * validator for endpoints attribute.
 */
class EndpointsValidator implements ValidatorInterface
{
    /**
     * Validate an ENDPOINTS attribute value
     * 
     * @param string $value
     * @param mixed  $container
     * @return bool
     */
    public function validate($value, $container)
    {
        // Validate that container is an AbstractActor type
        Util::subclassOf($container, AbstractActor::class, true);

        // A link to a JSON-LD document 
        if (Util::validateUrl($value)) {
            return true;
        }

        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

        // A map
        return is_object($value)
            ? $this->validateObject($value)
            : false;
    }

    /**
     * Validate an endpoints mapping
     * 
     * @param object $map
     * @return bool
     */
    protected function validateObject($item)
    {
        foreach ($item as $key => $value) {
            // Mapping has a bad key format
            if (!is_string($key)) {
                return false;
            }

            switch ($key) {
                case 'proxyUrl':
                case 'oauthAuthorizationEndpoint':
                case 'oauthTokenEndpoint':
                case 'provideClientKey':
                case 'signClientKey':
                case 'sharedInbox':
                    if (!Util::validateUrl($value)) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }
}
