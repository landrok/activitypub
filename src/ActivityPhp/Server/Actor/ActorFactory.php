<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Actor;

use ActivityPhp\Server;
use ActivityPhp\Server\Http\GuzzleActivityPubClient;
use ActivityPhp\Type;
use Exception;

/**
 * \ActivityPhp\Server\ActorFactory provides a factory for server-side 
 * actor.
 */
final class ActorFactory
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Create an actor from its profile url
     * 
     * @param  string $url
     * @return \ActivityPhp\Type\Extended\AbstractActor
     * @throws \Exception if actor does not exist
     */
    public function create(string $url)
    {
        // Is it a local actor?
        if (parse_url($url, PHP_URL_HOST) == $this->server->config('instance.host')
         && parse_url($url, PHP_URL_PORT) == $this->server->config('instance.port')
        ) {
            return $this->createLocalActor($url);
        }
        
        
        $content = $this->server->getClient()->get($url);

        if (!is_array($content)
            || !count($content)
            || !isset($content['type'])
        ) {
            throw new Exception('Actor fetching failed');
        }

        // @todo check AbstractActor type
        $actor = $this->server->getTypeFactory()->create($content['type'], $content);

        // An actor must have a set of properties to be a valid
        // ActivityPhp profile
        foreach (['id', 'preferredUsername'] as $property) {
            if ($actor->has($property)
                && !is_null($actor->$property)
            ) {
                continue;
            }

            throw new Exception(
                "Actor MUST have a '$property' property."
            );
        }

        return $actor;
    }

    /**
     * Create an actor type from a profile id
     * 
     * @param  string $url
     * @return string
     */
    public function createLocalActor(string $url)
    {
        return $this->server->getTypeFactory()->create([
            'id'   => $url,
            'type' => 'Person',
            'preferredUsername' => $this->extractHandle($url)
        ]);
    }

    /**
     * Parse an actor handle from a profile id
     * 
     * @param  string $url
     * @return string
     */
    public function extractHandle(string $url)
    {
        $pattern = $this->server->config('instance.actorPath');
        $pattern = str_replace(
            ['<handle>', '@'],
            ['([\w\d\-]+)', '\@'],
            $pattern
        );

        if (!preg_match("#{$pattern}#", $url, $matches)) {
            throw new Exception(sprintf(
                'Failed to extract username from URL "%s", pattern="%s"',
                $url,
                $pattern
            ));
        }

        return $matches[1];
    }
}
