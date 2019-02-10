<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Server\Http;

use ActivityPub\Server\CacheHelper;
use Exception;
use GuzzleHttp\Client;

/**
 * Request handler
 */ 
class Request
{
    const HTTP_HEADER_ACCEPT = 'application/activity+json';

    /**
     * @var string HTTP method
     */
    protected $method = 'GET';

    /**
     * Allowed HTTP methods
     * 
     * @var array
     */
    protected $allowedMethods = [
        'GET', 'POST'
    ];

    /**
     * HTTP client
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Set HTTP client
     */
    public function __construct()
    {
        $this->client = new Client([
            'timeout'  => 10.0,
            'headers' => [
                'Accept'     => self::HTTP_HEADER_ACCEPT,
            ]
        ]);
    }

    /**
     * Set HTTP methods
     * 
     * @param string $method
     */
    protected function setMethod(string $method)
    {
        if (in_array($method, $this->allowedMethods)) {
            $this->method = $method;
        }
    }

    /**
     * Get HTTP methods
     * 
     * @return string
     */
    protected function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Execute a GET request
     * 
     * @param  string $url
     * @return string
     */
    public function get(string $url)
    {
        if (CacheHelper::has($url)) {
            return CacheHelper::get($url);
        }

        $content = $this->client->get($url)->getBody()->getContents();

        CacheHelper::set($url, $content);

        return $content;
    }
}
