<?php

declare(strict_types=1);

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
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

/**
 * \ActivityPhp\Server is the entry point for server processes.
 */
final class Server
{
    /**
     * @var self
     */
    private static $singleton;

    /**
     * @var array<\ActivityPhp\Server\Actor>
     */
    private $actors = [];

    /**
     * @var array<\ActivityPhp\Server\Actor\Inbox>
     */
    private $inboxes = [];

    /**
     * @var array<\ActivityPhp\Server\Actor\Outbox>
     */
    private $outboxes = [];

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    private $logger;

    /**
     * @var \ActivityPhp\Server\Configuration|null
     */
    private $configuration;

    /**
     * Server constructor
     *
     * @param array<string,mixed> $config Server configuration
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
     */
    public function logger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Get cache instance
     */
    public function cache(): ?CacheItemPoolInterface
    {
        return $this->cache;
    }

    /**
     * Get a configuration handler
     *
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
     */
    public function inbox(string $handle): Inbox
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
     */
    public function outbox(string $handle): Outbox
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
     */
    public function actor(string $handle): Actor
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
     *
     * @param array<string,string|int|array<string>> $settings
     */
    public static function server(array $settings = []): self
    {
        if (is_null(self::$singleton)) {
            self::$singleton = new self($settings);
        }

        return self::$singleton;
    }
}
