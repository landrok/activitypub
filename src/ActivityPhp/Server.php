<?php

namespace ActivityPhp;

use ActivityPhp\Server\Actor;
use ActivityPhp\Server\Actor\Inbox;
use ActivityPhp\Server\Actor\Outbox;
use ActivityPhp\Server\Configuration;
use ActivityPhp\Server\Http\ActivityPubClientInterface;
use ActivityPhp\Server\Http\NormalizerInterface;
use ActivityPhp\Server\Http\WebFingerClient;
use ActivityPhp\Server\Http\DenormalizerInterface;
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
     * @var WebFingerClient
     */
    private $webFingerClient;

    /**
     * @var TypeFactory
     */
    private $typeFactory;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * Server constructor
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param ActivityPubClientInterface $activityPubClient
     * @param WebFingerClient $webFingerClient
     * @param TypeFactory $typeFactory
     * @param NormalizerInterface $normalizer
     * @param DenormalizerInterface $denormalizer
     * @param array $config Server configuration
     * @throws \Exception
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ActivityPubClientInterface $activityPubClient,
        WebFingerClient $webFingerClient,
        TypeFactory $typeFactory,
        NormalizerInterface $normalizer,
        DenormalizerInterface $denormalizer,
        array $config = []
    ) {
        $this->configuration = new Configuration($config);

        $this->responseFactory = $responseFactory;
        $this->activityPubClient = $activityPubClient;
        $this->webFingerClient = $webFingerClient;
        $this->typeFactory = $typeFactory;
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
    }

    /**
     * Get a configuration handler
     *
     * @param string $parameter
     * @return Configuration\InstanceConfiguration|Configuration\HttpConfiguration|string
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

        $this->actors[$handle] = new Actor($handle, $this, $this->webFingerClient);

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

    /**
     * @return TypeFactory
     */
    public function getTypeFactory(): TypeFactory
    {
        return $this->typeFactory;
    }

    /**
     * @return NormalizerInterface
     */
    public function getNormalizer(): NormalizerInterface
    {
        return $this->normalizer;
    }

    /**
     * @return DenormalizerInterface
     */
    public function getDenormalizer(): DenormalizerInterface
    {
        return $this->denormalizer;
    }
}
