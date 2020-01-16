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
use ActivityPhp\Server\Configuration;
use ActivityPhp\Server\Http\ActivityPubClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class Server
{
    /**
     * @var Actor[]
     */
    protected $actors = [];

    /**
     * @var Inbox[]
     */
    protected $inboxes = [];

    /**
     * @var Outbox[]
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ActivityPubClientInterface
     */
    private $activityPubClient;

    /**
     * Server constructor
     *
     * @param array $config Server configuration
     * @param ResponseFactoryInterface $responseFactory
     * @param ActivityPubClientInterface $activityPubClient
     * @throws \Exception
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ActivityPubClientInterface $activityPubClient,
        array $config = []
    ) {
        $this->configuration = new Configuration($config);

        $this->responseFactory = $responseFactory;
        $this->activityPubClient = $activityPubClient;
    }

    /**
     * Get a configuration handler
     *
     * @param string $parameter
     * @return Configuration\LoggerConfiguration|Configuration\InstanceConfiguration|Configuration\HttpConfiguration|string
     * @throws \Exception
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
     * @return Inbox
     */
    public function inbox(string $handle)
    {
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
     * @param string $handle
     * @return \ActivityPhp\Server\Actor
     * @throws \Exception
     */
    public function actor(string $handle)
    {
        if (isset($this->actors[$handle])) {
            return $this->actors[$handle];
        }

        $this->actors[$handle] = new Actor($handle, $this);

        return $this->actors[$handle];
    }

    /**
     * @return ResponseFactoryInterface
     */
    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->responseFactory;
    }

    /**
     * @return ActivityPubClientInterface
     */
    public function getClient(): ActivityPubClientInterface
    {
        return $this->activityPubClient;
    }
}
