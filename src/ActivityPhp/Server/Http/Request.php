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
use ActivityPhp\Server\Cache\CacheHelper;
use Exception;
use GuzzleHttp\Client;

/**
 * Request handler
 */
class Request
{
    const HTTP_HEADER_ACCEPT = 'application/activity+json,application/ld+json,application/json';

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
     * Number of allowed retries
     *
     * -1: unlimited
     * 0 : never retry
     * >0: throw exception after this number of retries
     */
    protected $maxRetries = 0;

    /**
     * Number of seconds to wait before retrying
     */
    protected $sleepBeforeRetry = 5;

    /**
     * Current retries counter
     */
    protected $retryCounter = 0;

    /**
     * Set HTTP client
     *
     * @param float|int $timeout
     * @param string $agent
     */
    public function __construct($timeout = 10.0, $agent = '')
    {
        $headers = ['Accept' => self::HTTP_HEADER_ACCEPT];

        if ($agent) {
            $headers['User-Agent'] = $agent;
        }

        $this->client = new Client([
            'timeout' => $timeout,
            'headers' => $headers
        ]);
    }

    /**
     * Set Max retries after a sleeping time
     */
    public function setMaxRetries(int $maxRetries, int $sleepBeforeRetry = 5): self
    {
        $this->maxRetries = $maxRetries;
        $this->sleepBeforeRetry = $sleepBeforeRetry;

        return $this;
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

        try {
            $content = $this->client->get($url)->getBody()->getContents();
        } catch (Exception $e) {
            Server::server()->logger()->error(
                __METHOD__ . ':failure',
                ['url' => $url, 'message' => $e->getMessage()]
            );
            if ($this->maxRetries === -1
             || $this->retryCounter < $this->maxRetries
            ) {
                $this->retryCounter++;
                Server::server()->logger()->info(
                    __METHOD__ . ':retry#' . $this->retryCounter,
                    ['url' => $url]
                );
                sleep($this->sleepBeforeRetry);
                return $this->get($url);
            }

            throw new Exception($e->getMessage());
        }

        CacheHelper::set($url, $content);

        $this->retryCounter = 0;

        return $content;
    }
}
