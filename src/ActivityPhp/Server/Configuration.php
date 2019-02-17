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
     * @var null|\ActivityPhp\Server\Configuration\HttpConfiguration
     */
    protected $http;

    /**
     * @var null|\ActivityPhp\Server\Configuration\InstanceConfiguration
     */
    protected $instance;

    /**
     * @var null|\ActivityPhp\Server\Configuration\LoggerConfiguration
     */
    protected $logger;

    /**
     * @var null|\ActivityPhp\Server\Configuration\CacheConfiguration
     */
    protected $cache;

    /**
     * Dispatch configuration parameters
     * 
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            if (!is_string($key)) {
                throw new Exception(
                    "Configuration key must be a string"
                );
            } elseif (!isset($this->$key) && !property_exists($this, $key)) {
                throw new Exception(
                    "Configuration handler '$key' does not exist"
                );                
            } elseif (!is_array($value)) {
                throw new Exception(
                    "Configuration value must be an array"
                );                
            } else {
                $handler = sprintf(
                    self::CONFIG_NS_PATTERN, ucfirst($key)
                );
                $this->$key = new $handler($value);
            }
        }
        
        // Create default configuration for each component
        foreach (['cache', 'logger', 'instance'] as $config) {
            if (is_null($this->$config)) {
                $handler = sprintf(
                    self::CONFIG_NS_PATTERN, ucfirst($config)
                );

                $this->$config = new $handler();
            }
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
