<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Configuration;

use ActivityPhp\Server;
use ActivityPhp\Version;

/**
 * Server HTTP configuration stack
 */ 
class HttpConfiguration extends AbstractConfiguration
{
    /**
     * @var float|int HTTP timeout for request
     */
    protected $timeout = 10.0;

    /**
     * @var string The User Agent.
     */
    protected $agent;

    /**
     * Dispatch configuration parameters
     * 
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        // Configure a default value for user agent
        if (is_null($this->agent)) {
            $this->agent = $this->getUserAgent();
        }
    }

    /**
     * Prepare a default value for user agent
     */
    private function getUserAgent(): string
    {
        $scheme = Server::server()->config('instance.scheme');
        $host = Server::server()->config('instance.host');
        $port = Server::server()->config('instance.port');

        if ($port == 443 && $scheme == 'https') {
            $port = null;
        }

        if ($port == 80 && $scheme == 'http') {
            $port = null;
        }

        $url = sprintf(
            '%s://%s%s',
            $scheme,
            $host,
            is_null($port) ? '' : ":{$port}"
        );
        
        return sprintf(
            '%s/%s (+%s)',
            Version::getRootNamespace(),
            Version::getVersion(),
            $url
        );
    }
}
