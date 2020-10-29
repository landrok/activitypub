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

use Exception;

/**
 * Server configuration dispatcher
 */ 
class Configuration
{
    const CONFIG_NS_PATTERN = '\ActivityPhp\Server\Configuration\%sConfiguration';

    /**
     * @var \ActivityPhp\Server\Configuration\HttpConfiguration
     */
    protected $http;

    /**
     * @var \ActivityPhp\Server\Configuration\InstanceConfiguration
     */
    protected $instance;

    /**
     * @var \ActivityPhp\Server\Configuration\LoggerConfiguration
     */
    protected $logger;

    /**
     * @var \ActivityPhp\Server\Configuration\CacheConfiguration
     */
    protected $cache;

    /**
     * @var \ActivityPhp\Server\Configuration\DialectsConfiguration
     */
    protected $dialects;

    /**
     * @var \ActivityPhp\Server\Configuration\OntologiesConfiguration
     */
    protected $ontologies;

    /**
     * Dispatch configuration parameters
     */
    public function dispatchParameters(array $params): void
    {
        // Create default configuration for each component
        foreach ([
                'cache',
                'logger',
                'instance',
                'http',
                'dialects',
                'ontologies'
            ] as $config
        ) {

            if (isset($params[$config]) && !is_array($params[$config])) {
                throw new Exception(
                    "Configuration value for '$config' must be an array"
                );                
            }

            if (is_null($this->$config)) {
                $handler = sprintf(
                    self::CONFIG_NS_PATTERN, ucfirst($config)
                );

                $this->$config = new $handler(
                    isset($params[$config]) && is_array($params[$config])
                        ? $params[$config]
                        : []
                );
            }

            // Clean params
            if (isset($params[$config])) {
                unset($params[$config]);
            }
        }

        // Check if some parameters have been ignored.
        if (count($params)) {
            throw new Exception(
                "Following configuration parameters have been ignored:\n"
                 . json_encode($params, JSON_PRETTY_PRINT)
            );                
        }        
    }
    /**
     * Get a configuration dedicated handler
     * 
     * @return \ActivityPhp\Server\Configuration\LoggerConfiguration
     *       | \ActivityPhp\Server\Configuration\InstanceConfiguration
     *       | \ActivityPhp\Server\Configuration\HttpConfiguration
     *       | string
     * @throws \Exception
     */
    public function getConfig(string $parameter)
    {
        // Get configuration identifier
        $xpt = explode('.', $parameter, 2);
        
        if (isset($this->{$xpt[0]})) {
            if (!isset($xpt[1])) {
                return $this->{$xpt[0]};
            }
            return $this->{$xpt[0]}->get($xpt[1]);
        }

        throw new Exception(
            "Configuration handler '{$xpt[0]}' does not exist"
        );   
    }
}
