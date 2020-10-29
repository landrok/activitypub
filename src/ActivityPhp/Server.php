<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp;

use ActivityPhp\Server\Actor;
use ActivityPhp\Server\Actor\Inbox;
use ActivityPhp\Server\Actor\Outbox;
use ActivityPhp\Server\Cache\CacheHelper;
use ActivityPhp\Server\Configuration;
use Exception;

/**
 * \ActivityPhp\Server is the entry point for server processes.
 */ 
class Server
{
    /**
     * @var self
     */
    private static $singleton;

    /**
     * @var \ActivityPhp\Server\Actor[]
     */
    protected $actors = [];

    /**
     * @var \ActivityPhp\Server\Actor\Inbox[]
     */
    protected $inboxes = [];

    /**
     * @var \ActivityPhp\Server\Actor\Outbox[]
     */
    protected $outboxes = [];

    /**
     * @var null|\Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var null|\ActivityPhp\Server\Configuration
     */
    protected $configuration;

    /**
     * Server constructor
     * 
     * @param array $config Server configuration
     */
    public function __construct(array $config = [])
    {
        self::$singleton = $this;
        $this->configuration = new Configuration();
        $this->configuration->dispatchParameters($config);
        $this->logger = $this->config('logger')->createLogger();

        CacheHelper::setPool(
            $this->config('cache')
        );
    }

    /**
     * Get logger instance
     * 
     * @return null|\Psr\Log\LoggerInterface
     */
    public function logger()
    {
        return $this->logger;
    }

    /**
     * Get cache instance
     * 
     * @return null|\Psr\Cache\CacheItemPoolInterface
     */
    public function cache()
    {
        return $this->cache;
    }

    /**
     * Get a configuration handler
     * 
     * @param  string $parameter
     * @return \ActivityPhp\Server\Configuration\LoggerConfiguration
     *       | \ActivityPhp\Server\Configuration\InstanceConfiguration
     *       | \ActivityPhp\Server\Configuration\HttpConfiguration
     *       | string
     */
    public function config(string $parameter)
    {
        return $this->configuration->getConfig($parameter);
    }

    /**
     * Get an inbox instance
     * It's a local instance
     * 
     * @param  string $handle An actor name
     * @return \ActivityPhp\Server\Actor\Inbox
     */
    public function inbox(string $handle)
    {
        $this->logger()->info($handle . ':' . __METHOD__);

        if (isset($this->inboxes[$handle])) {
            return $this->inboxes[$handle];
        }

        // Build actor
        $actor = $this->actor($handle);

        $this->inboxes[$handle] = new Inbox($actor, $this);

        return $this->inboxes[$handle];
    }

    /**
     * Get an outbox instance
     * It may be a local or a distant outbox.
     * 
     * @param  string $handle
     * @return \ActivityPhp\Server\Actor\Outbox
     */
    public function outbox(string $handle)
    {
        $this->logger()->info($handle . ':' . __METHOD__);

        if (isset($this->outboxes[$handle])) {
            return $this->outboxes[$handle];
        }

        // Build actor
        $actor = $this->actor($handle);

        $this->outboxes[$handle] = new Outbox($actor, $this);

        return $this->outboxes[$handle];
    }

    /**
     * Build an server-oriented actor object
     * 
     * @param  string $handle
     * @return \ActivityPhp\Server\Actor
     */
    public function actor(string $handle)
    {
        $this->logger()->info($handle . ':' . __METHOD__);

        if (isset($this->actors[$handle])) {
            return $this->actors[$handle];
        }

        $this->actors[$handle] = new Actor($handle, $this);

        return $this->actors[$handle];
    }

    /**
     * Get server instance with a static call
     */
    public static function singleton(array $settings = []): self
    {
        if (is_null(self::$singleton)) {
            self::$singleton = new self($settings);
        }

        return self::$singleton;
    }
}
