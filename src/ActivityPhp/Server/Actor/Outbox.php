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
use ActivityPhp\Server\Activity\HandlerInterface;
use ActivityPhp\Server\Actor;
use ActivityPhp\Server\Helper;
use ActivityPhp\Type;
use ActivityPhp\Type\Core\AbstractActivity;
use ActivityPhp\Type\Util;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A server-side outbox
 */ 
class Outbox extends AbstractBox
{
    /**
     * Outbox constructor
     * 
     * @param  \ActivityPhp\Server\Actor $actor An actor
     * @param  \ActivityPhp\Server $server
     */
    public function __construct(Actor $actor, Server $server)
    {
        parent::__construct($actor, $server);
    }

    /**
     * Get items from an outbox
     *
     * @param string $url
     * @return Type\AbstractObject
     * @throws Exception
     */
    public function getPage(string $url)
    {
        $response = $this->server->getClient()->get($url);

        return Type::create(Util::decodeJson($response));
    }

    /**
     * Fetch an outbox
     *
     * @return \ActivityPhp\Type\Core\OrderedCollection
     * @throws Exception
     */
    public function get()
    {
        if (!is_null($this->orderedCollection)) {
            return $this->orderedCollection;
        }
        
        if (null === $url = $this->actor->get('outbox')) {
            throw new Exception('No outbox url available for actor');
        }

        $response = $this->server->getClient()->get($url);

        $this->orderedCollection = Type::create(Util::decodeJson($response));
        
        return $this->orderedCollection;
    }

    /**
     * Post a message to the world
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function post(ServerRequestInterface $request)
    {
        try {
            // Check accept header
            Helper::validateAcceptHeader(
                $request->getHeader('accept'),
                true
            );

            // Check current actor can post

            // Get content
            $payload = Util::decodeJson((string) $request->getBody());

            // Cast as an ActivityStreams type
            $activity = Type::create($payload);
        } catch (Exception $exception) {
            $response = $this->server->getResponseFactory()->createResponse(400);
            $response->getBody()->write($exception->getMessage());

            return $response;
        }

        // If it's not an activity, wrap into a Create activity
        if (!Util::subclassOf($activity, AbstractActivity::class)) {
            $activity = $this->wrapObject($activity);
        }

        // Clients submitting the following activities to an outbox MUST 
        // provide the object property in the activity: 
        //  Create, Update, Delete, Follow, 
        //  Add, Remove, Like, Block, Undo
        if (!isset($activity->object)) {
            throw new Exception("A posted activity must have an 'object' property");
        }

        // Prepare an activity handler
        $handler = sprintf('\ActivityPhp\Server\Activity\%sHandler', $activity->type);

        if (!class_exists($handler)) {
            throw new Exception(sprintf("No handler has been defined for this activity '%s'", $activity->type));
        }

        // Handle activity
        $handler = new $handler($activity, $this->server->getResponseFactory());

        if (!$handler instanceof HandlerInterface) {
            throw new Exception(sprintf("An activity handler must implement %s", HandlerInterface::class));
        }

        // Return a standard HTTP Response
        return $handler->handle();
    }
}
