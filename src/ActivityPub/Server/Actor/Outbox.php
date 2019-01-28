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
use ActivityPub\Server\Activity\HandlerInterface;
use ActivityPub\Server\Actor;
use ActivityPub\Server\Helper;
use ActivityPub\Type;
use ActivityPub\Type\Core\AbstractActivity;
use ActivityPub\Type\Util;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A server-side outbox
 */ 
class Outbox extends AbstractBox
{
    /**
     * Outbox constructor
     * 
     * @param  string $name An actor's name
     * @param  \ActivityPub\Server $server
     */
    public function __construct(Actor $actor, Server $server)
    {
        $server->logger()->info(
            $actor->getType()->preferredUsername . ':' . __METHOD__
        );
        parent::__construct($actor, $server);
    }

    /**
     * Post a message to the world
     * 
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \ActivityPub\Server\Activity\ActivityHandler
     */
    public function post(Request $request)
    {
        try {
            // Check accept header
            if (!Helper::validateAcceptHeader(
                    $request->headers->get('accept')
                )
            ) {
                throw new Exception(
                    "HTTP Accept header error. Given: '$accept'"
                );
            }

            // Check current actor can post
            
            
            // Get content
            $payload = Util::decodeJson(
                $request->getContent()
            );

            // Cast as an ActivityStreams type
            $activity = Type::create($payload);

        } catch (Exception $exception) {
            $this->getServer()->logger()->error(
                $this->actor->getType()->preferredUsername. ':' . __METHOD__, [
                    $exception->getMessage()
                ]
            );

            return new Response('', 400);
        }
        
        
        
       //print_r($payload); die;



        // Log
        $this->getServer()->logger()->debug(
            $this->actor->getType()->preferredUsername. ':' . __METHOD__ . '(starting)', 
            $activity->toArray()
        );

        // If it's not an activity, wrap into a Create activity
        if (!Util::subclassOf($activity, AbstractActivity::class)) {
            $activity = $this->wrapObject($activity);
        }

        // Clients submitting the following activities to an outbox MUST 
        // provide the object property in the activity: 
        //  Create, Update, Delete, Follow, 
        //  Add, Remove, Like, Block, Undo
        if (!isset($activity->object)) {
            throw new Exception(
                "A posted activity must have an 'object' property"
            );
        }

        // Prepare an activity handler
        $handler = sprintf(
            '\ActivityPub\Server\Activity\%sHandler',
            $activity->type
        );

        if (!class_exists($handler)) {
            throw new Exception(
                "No handler has been defined for this activity "
                . "'{$activity->type}'"
            );
        }

        // Handle activity
        $handler = new $handler($activity);

        if (!($handler instanceof HandlerInterface)) {
            throw new Exception(
                "An activity handler must implement "
                . HandlerInterface::class
            );
        }

        // Log
        $this->getServer()->logger()->debug(
            $this->actor->getType()->preferredUsername. ':' . __METHOD__ . '(posted)', 
            $activity->toArray()
        );

        // Return a standard HTTP Response
        return $handler->handle()->getResponse();
    }
}
