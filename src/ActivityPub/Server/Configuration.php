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

use Exception;

/**
 * Server configuration dispatcher
 */ 
class Configuration
{
    const CONFIG_NS_PATTERN = '\ActivityPub\Server\Configuration\%sConfiguration';

    /**
     * @var null|\ActivityPub\Server\Configuration\HttpConfiguration
     */
    protected $http;

    /**
     * @var null|\ActivityPub\Server\Configuration\InstanceConfiguration
     */
    protected $instance;

    /**
     * @var null|\ActivityPub\Server\Configuration\LoggerConfiguration
     */
    protected $logger;

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
        foreach (['http', 'logger', 'instance'] as $config) {
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
     * @return \ActivityPub\Server\Configuration\BaseConfiguration
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
