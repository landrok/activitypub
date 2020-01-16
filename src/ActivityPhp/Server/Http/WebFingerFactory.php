<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Http;

use ActivityPhp\Server;
use ActivityPhp\Type\Util;
use Exception;

/**
 * A simple WebFinger discoverer tool
 */ 
class WebFingerFactory
{
    const WEBFINGER_URL = '%s://%s%s/.well-known/webfinger?resource=acct:%s';

    /**
     * @var null|\ActivityPhp\Server
     */
    protected static $server;

    /**
     * @var array An array of key => value. 
     * Keys are handle, values are WebFinger instances.
     */
    protected static $webfingers = [];

    /**
     * Get a profile via WebFinger protocol
     * 
     * @param string $handle
     * @param string $scheme Only for testing purpose
     * @return \ActivityPhp\Server\Http\WebFinger
     * @throws \Exception if handle is malformed.
     */
    public static function get(string $handle, string $scheme = 'https')
    {
        if (!preg_match(
                '/^@?(?P<user>[\w\-\.]+)@(?P<host>[\w\.\-]+)(?P<port>:[\d]+)?$/',
                $handle,
                $matches
            )
        ) {
            throw new Exception(
                "WebFinger handle is malformed '{$handle}'"
            );
        }

        // Unformat Mastodon handle @user@host => user@host
        $handle = strpos($handle, '@') === 0
            ? substr($handle, 1) : $handle;

        // Build a WebFinger URL
        $url = sprintf(
            self::WEBFINGER_URL,
            $scheme,
            $matches['host'],
            isset($matches['port']) ? $matches['port'] : '',
            $handle
        );

        $content = Util::decodeJson(
            (new GuzzleActivityPubClient(
                self::$server->config('http.timeout')
            ))->get($url)
        );

        if (!is_array($content) || !count($content)) {
            throw new Exception('WebFinger fetching has failed');
        }

        self::$webfingers[$handle] = new WebFinger($content);

        return self::$webfingers[$handle];
    }

    /**
     * Inject a server instance
     * 
     * @param  \ActivityPhp\Server $server
     */
    public static function setServer(Server $server)
    {
        self::$server = $server;
    }
}
