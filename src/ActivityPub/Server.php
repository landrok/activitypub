<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub;

use ActivityPub\Server\Federation\Actor;
use ActivityPub\Server\WebFinger;

use ActivityPub\Server\Configuration;
use ActivityPub\Server\Actor\Inbox;
use ActivityPub\Server\Actor\Outbox;
use Exception;

/**
 * \ActivityPub\Server is the entry point for server processes.
 */ 
class Server
{
    /**
     * @var \ActivityPub\Server\Federation\Actor[]
     */
    protected $inboxes = [];

    /**
     * @var \ActivityPub\Server\Federation\Actor[]
     */
    protected $outboxes = [];

    /**
     * @var null|\Monolog\Logger
     */
    protected $logger;

    /**
     * @var null|\ActivityPub\Server\Configuration
     */
    protected $configuration;

    /**
     * Server constructor
     * 
     * @param array $config Server configuration
     */
    public function __construct(array $config = [])
    {
        $this->configuration = new Configuration($config);
        $this->logger = $this->config('logger')->createLogger();
    }

    /**
     * Get logger instance
     * 
     * @return \Monolog\Logger
     */
    public function logger()
    {
        return $this->logger;
    }

    /**
     * Get a configuration handler
     * 
     * @param  string $parameter
     * @return \ActivityPub\Server\Configuration
     */
    public function config(string $parameter)
    {
        return $this->configuration->getConfig($parameter);
    }

    /**
     * Getting an inbox instance
     * It can be a local or a distant inbox.
     * 
     * @param  string $name An actor name
     * @return \ActivityPub\Server\Actor\Inbox
     */
    public function inbox(string $name)
    {
        if (isset($this->inboxes[$name])) {
            return $this->inboxes[$name];
        }

        $this->inboxes[$name] = new Inbox($name);

        return $this->inboxes[$name];
    }

    /**
     * Getting an outbox instance
     * It can be a local or a distant outbox.
     * 
     * @param  string $username
     * @return \ActivityPub\Server\Actor\Outbox
     */
    public function outbox(string $name)
    {
        $this->logger()->info($name . ':' . __METHOD__);

        if (isset($this->outboxes[$name])) {
            return $this->outboxes[$name];
        }

        $this->outboxes[$name] = new Outbox($name, $this);

        return $this->outboxes[$name];
    }
}
