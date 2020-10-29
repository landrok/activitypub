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

use Exception;

/**
 * Abstract methods for configurations classes
 */ 
abstract class AbstractConfiguration
{
    /**
     * Dispatch configuration parameters
     */
    public function __construct(array $params = [])
    {
        $this->setArray($params);
    }
    
    /**
     * Get a config value
     *
     * @param  string $key
     * @return string A configuration value
     */
    public function get($key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        }

        throw new Exception("'$key' parameter does not exist");
    }

    /**
     * Set configuration values by array
     *
     * @return void
     */
    public function setArray(array $settings)
    {
        foreach ($settings as $key => $value) {
            if (!is_string($key)) {
                throw new Exception(
                    "Configuration key must be a string"
                );
            } elseif (!isset($this->$key) && !property_exists($this, $key)) {
                throw new Exception(
                    "Configuration parameter '$key' does not exist"
                );                
            } else {
                // @todo Should be validated
                $this->$key = $value;
            }
        }
    }

    
}
