<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Server;

use ActivityPub\Type;
use DateInterval;
use DateTime;
use Exception;

/**
 * \ActivityPub\Server\Helper provides global helper methods for a server
 * instance.
 */
abstract class Helper
{
    /**
     * An array of allowed Accept HTTP headers
     * 
     * @see https://www.w3.org/TR/activitypub/#client-to-server-interactions
     * 
     * @var string[]
     */
    protected static $acceptHeaders = [
        'application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
        'application/activity+json'
    ];

    /**
     * Validate HTTP Accept headers
     * 
     * @param  null|string|array $accept
     * @return bool
     */
    public static function validateAcceptHeader($accept)
    {
        if (is_string($accept)) {
            return in_array($accept, self::$acceptHeaders);
        } elseif (is_array($accept)) {
            return count(
                array_intersect($accept, self::$acceptHeaders)
            ) > 0;
        }
    }
}
