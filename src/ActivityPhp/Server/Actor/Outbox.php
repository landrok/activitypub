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
        $server->logger()->info(
            $actor->get()->preferredUsername . ':' . __METHOD__
        );
        parent::__construct($actor, $server);
    }

    /**
     * Get items from an outbox
     * 
     * @param  string $page
     * @return array
     */
    public function getPage(string $url)
    {
        $this->server->logger()->info(
            $this->actor->webfinger()->getHandle() . ':' . __METHOD__, 
            [$url]
        );

        return Type::create(Helper::fetch($url));
    }

    /**
     * Fetch an outbox
     * 
     * @return \ActivityPhp\Type\Core\OrderedCollection
     */
    public function get()
    {
        if (!is_null($this->orderedCollection)) {
            return $this->orderedCollection;
        }

        $this->server->logger()->info(
            $this->actor->webfinger()->getHandle() . ':' . __METHOD__
        );

        $url = $this->actor->get('outbox');
        
        if (is_null($url)) {
            $this->server->logger()->warning(
                $this->actor->webfinger()->getHandle() 
                . ': Outbox is not defined'
            );
            return;
        }            
        
        $this->orderedCollection = Type::create(
            Helper::fetch($url)
        );
        
        return $this->orderedCollection;
    }

    /**
     * Post a message to the world
     * 
     * @param ServerRequestInterface $request
     * @return ResponseInterface
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

        if (!($handler instanceof HandlerInterface)) {
            throw new Exception(sprintf("An activity handler must implement %s", HandlerInterface::class));
        }

        // Return a standard HTTP Response
        return $handler->handle();
    }
}
