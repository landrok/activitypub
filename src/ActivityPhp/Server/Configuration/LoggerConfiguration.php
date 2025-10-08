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
use Psr\Log\NullLogger;

/**
 * Logger configuration stack
 */
class LoggerConfiguration extends AbstractConfiguration
{
    /**
     * @var string Logger class name
     */
    protected $driver = '\Psr\Log\NullLogger';

    /**
     * @var string Logger stream
     */
    protected $stream = 'php://stdout';

    /**
     * @var string
     */
    protected $channel = 'global';

    /**
     * Dispatch configuration parameters
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * Create logger instance
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function createLogger()
    {
        if (!class_exists($this->driver)) {
            throw new Exception(
                "Logger driver does not exist. Given='{$this->driver}'"
            );
        }

        $logger = new $this->driver();

        return $logger;
    }
}
