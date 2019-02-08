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

use ActivityPub\Server;
use ActivityPub\Server\Http\Request;
use ActivityPub\Server\Http\WebFingerFactory as WebFinger;
use ActivityPub\Type;
use ActivityPub\Type\Util;
use Exception;

/**
 * A server-oriented actor object
 */ 
class Actor
{
    /**
     * @var \ActivityPub\Server
     */
    protected $server;

    /**
     * @var \ActivityPub\Type\Extended\AbstractActor
     */
    protected $actor;

    /**
     * @var null|string Actor's URL
     */
    protected $url;

    /**
     * Construct Actor instance based upon a WebFinger discovery if
     * an handle-like is provided. Otherwise, it checks an ActivityPub
     * profile id if it's an URL.
     * 
     * @param  string $handle  URL or a WebFinger handle
     * @param  \ActivityPub\Server $server
     */
    public function __construct(string $handle, Server $server)
    {
        $this->server = $server;

        // Is a valid handle?
        if ($this->isHandle($handle)) {
            // testing only
            $scheme = $this->server->config('instance.debug')
                ? 'http' : 'https';
            $webfinger = WebFinger::get($handle, $scheme);
            $this->url = $webfinger->getProfileId();
        // Is an id?
        } elseif (Util::validateUrl($handle)) {
            $this->url = $handle;
        }

        if (is_null($this->url)) {
            throw new Exception(
                "Invalid Actor handle: " . print_r($handle, true)
            );
        }

        $this->make();
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
            '/^@?(?P<user>[\w\-]+)@(?P<host>[\w\.\-]+)(?P<port>:[\d]+)?$/',
            $handle
        );
    }

    /**
     * Build a profile
     *
     * @throw \Exception if there is a problem wihle fetching profile or 
     *  if provided profile is malformed
     */
    private function make()
    {
        $content = json_decode(
            (new Request())->get($this->url),
            true
        );

        if (!is_array($content)
            || !count($content)
            || !isset($content['type'])
        ) {
            throw new Exception('Actor fetching failed');
        }

        $this->actor = Type::create($content['type'], $content);

        // @todo check AbstractActor type

        // An actor must have a set of properties to be a valid
        // ActivityPub profile
        foreach (['id', 'preferredUsername'] as $property) {
            if ($this->actor->has($property)
                && !is_null($this->actor->$property)
            ) {
                continue;
            }

            throw new Exception(
                "Actor MUST have a '$property' property."
            );
        }
    }

    /**
     * Get ActivityStream Actor
     * 
     * @param  null|string $property
     * @return \ActivityPub\Type\Extended\AbstractActor
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
     * Get WebFinger bound to a profile
     * 
     * @return \ActivityPub\Server\Http\WebFinger
     */
    public function webfinger()
    {
        // testing only
        $scheme = $this->server->config('instance.debug')
            ? 'http' : 'https';

        $port = !is_null(parse_url($this->actor->id, PHP_URL_PORT))
            ? ':' . parse_url($this->actor->id, PHP_URL_PORT)
            : '';

        $handle = sprintf(
            '%s@%s%s',
            $this->actor->preferredUsername,
            parse_url($this->actor->id, PHP_URL_HOST),
            $port
        );

        return WebFinger::get($handle, $scheme);
    }
}
