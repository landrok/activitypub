<?php

namespace ActivityPhp\Server;

use ActivityPhp\Server;
use ActivityPhp\Server\Actor\ActorFactory;
use ActivityPhp\Type\Util;
use Exception;

/**
 * A server-oriented actor object
 */ 
class Actor
{
    /**
     * @var \ActivityPhp\Server
     */
    protected $server;

    /**
     * @var Server\Http\WebFingerClient
     */
    protected $webfingerClient;

    /**
     * @var \ActivityPhp\Type\Extended\AbstractActor
     */
    protected $actor;

    /**
     * Construct an Actor instance based upon a WebFinger discovery if
     * an handle-like is provided. Otherwise, it checks an ActivityPhp
     * profile id if it's an URL.
     *
     * @param string $handle URL or a WebFinger handle
     * @param \ActivityPhp\Server $server
     * @param Http\WebFingerClient $webFingerClient
     * @throws Exception
     */
    public function __construct(string $handle, Server $server, Server\Http\WebFingerClient $webFingerClient)
    {
        $this->server = $server;
        $this->webfingerClient = $webFingerClient;
        $url = null;

        // Is a valid handle?
        if ($this->isHandle($handle)) {
            // testing only
            $url = $webFingerClient->get($handle)->getProfileId();
        // Is an id?
        } elseif (Util::validateUrl($handle)) {
            $url = $handle;
        }

        if (is_null($url)) {
            throw new Exception(
                "Invalid Actor handle: " . print_r($handle, true)
            );
        }

        $this->createActor($url);
    }

    /**
     * Check that a string is a valid handle
     * 
     * @param  string $handle
     * @return bool
     */
    private function isHandle(string $handle)
    {
        return (bool)preg_match(
            '/^@?(?P<user>[\w\.\-]+)@(?P<host>[\w\.\-]+)(?P<port>:[\d]+)?$/',
            $handle
        );
    }

    /**
     * Build a profile
     *
     * @param string $url A profile id
     */
    private function createActor(string $url)
    {
        ActorFactory::setServer($this->server);
        $this->actor = ActorFactory::create($url);
    }

    /**
     * Get ActivityStream Actor
     * 
     * @param  null|string $property
     * @return \ActivityPhp\Type\Extended\AbstractActor
     *       | string
     *       | array
     */
    public function get($property = null)
    {
        if (is_null($property)) {
            return $this->actor;
        }

        return $this->actor->get($property);
    }

    /**
     * Get Actor's public key PEM
     * 
     * @return string|null
     */
    public function getPublicKeyPem()
    {
        if (!isset($this->actor->publicKey)
            || !is_array($this->actor->publicKey)
            || !isset($this->actor->publicKey['publicKeyPem'])
        ) {
            return null;
        }

        return $this->actor->publicKey['publicKeyPem'];
    }

    /**
     * Get WebFinger bound to a profile
     * 
     * @return \ActivityPhp\Server\Http\WebFinger
     */
    public function webfinger()
    {
        $port = !is_null(parse_url($this->actor->id, PHP_URL_PORT))
            ? ':' . parse_url($this->actor->id, PHP_URL_PORT)
            : '';

        $handle = sprintf(
            '%s@%s%s',
            $this->actor->preferredUsername,
            parse_url($this->actor->id, PHP_URL_HOST),
            $port
        );

        return $this->webfingerClient->get($handle);
    }
}
