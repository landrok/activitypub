<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Server\Actor;

use ActivityPub\Server;
use ActivityPub\Server\Http\Request;
use ActivityPub\Type;
use Exception;

/**
 * \ActivityPub\Server\ActorFactory provides a factory for server-side 
 * actor.
 */
abstract class ActorFactory
{
    /**
     * @var null|\ActivityPub\Server
     */
    protected static $server;

    /**
     * Create an actor from its profile url
     * 
     * @param  string $url
     * @return \ActivityPub\Type\Extended\AbstractActor
     * @throws \Exception if actor does not exist
     */
    public static function create(string $url)
    {
        $content = json_decode(
            (new Request())->get($url),
            true
        );

        if (!is_array($content)
            || !count($content)
            || !isset($content['type'])
        ) {
            throw new Exception('Actor fetching failed');
        }

        // @todo check AbstractActor type
        $actor = Type::create($content['type'], $content);

        // An actor must have a set of properties to be a valid
        // ActivityPub profile
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
     * Inject a server instance
     * 
     * @param  \ActivityPub\Server $server
     */
    public static function setServer(Server $server)
    {
        self::$server = $server;
    }
}
