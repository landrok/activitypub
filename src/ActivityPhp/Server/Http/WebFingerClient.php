<?php

namespace ActivityPhp\Server\Http;

use Exception;

/**
 * A simple WebFinger discoverer tool
 */ 
class WebFingerClient
{
    const WEBFINGER_URL = '%s://%s%s/.well-known/webfinger?resource=acct:%s';

    /**
     * @var ActivityPubClientInterface
     */
    protected $client;

    /**
     * @var array An array of key => value. 
     * Keys are handle, values are WebFinger instances.
     */
    protected $webfingers = [];

    /**
     * @var bool
     */
    protected $secure;

    public function __construct(ActivityPubClientInterface $client, bool $secure = true)
    {
        $this->client = $client;
        $this->secure = $secure;
    }

    /**
     * Get a profile via WebFinger protocol
     * 
     * @param string $handle
     * @param string $scheme Only for testing purpose
     * @return \ActivityPhp\Server\Http\WebFinger
     * @throws \Exception if handle is malformed.
     */
    public function get(string $handle)
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
            $this->secure ? 'https' : 'http',
            $matches['host'],
            isset($matches['port']) ? $matches['port'] : '',
            $handle
        );

        $content = $this->client->get($url);

        if (!is_array($content) || !count($content)) {
            throw new Exception('WebFinger fetching has failed');
        }

        $this->webfingers[$handle] = new WebFinger($content);

        return $this->webfingers[$handle];
    }
}
