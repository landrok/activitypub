<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server;

use ActivityPhp\Server\Http\Request as HttpRequest;
use ActivityPhp\Type;
use ActivityPhp\Type\Util;
use DateInterval;
use DateTime;
use Exception;

/**
 * \ActivityPhp\Server\Helper provides global helper methods for a server
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
        'application/activity+json',
        '*/*'
    ];

    /**
     * Validate HTTP Accept headers
     * 
     * @param  null|string|array $accept
     * @param  bool              $strict Strict mode
     * @return bool
     * @throws \Exception when strict mode enabled
     */
    public static function validateAcceptHeader($accept, $strict = false)
    {
        if (is_string($accept) 
            && in_array($accept, self::$acceptHeaders)
        ) {
            return true;
        } elseif (is_array($accept)
            && count(
                array_intersect($accept, self::$acceptHeaders)
            )
        ) {
            return true;
        }
        
        if (!$strict) {
            return false;
        }

        throw new Exception(
            sprintf(
                "HTTP Accept header error. Given: '%s'",
                $accept
            )
        );
    }

    /**
     * Fetch JSON content from an URL
     * 
     * @param  string    $url
     * @param  float|int $timeout
     * @return array
     */
    public static function fetch($url, $timeout = 10.0)
    {
        return Util::decodeJson(
           (new HttpRequest($timeout))->get($url)
        );
    }
}
