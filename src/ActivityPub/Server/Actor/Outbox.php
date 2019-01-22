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
use ActivityPub\Server\Response;
use ActivityPub\Type;
use ActivityPub\Type\AbstractObject;
use ActivityPub\Type\Core\AbstractActivity;
use ActivityPub\Type\Util;
use Exception;

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
    public function __construct(string $name, Server $server)
    {
        $server->logger()->info($name . ':' . __METHOD__);
        parent::__construct($name, $server);
    }

    /**
     * Post a message to the world
     * 
     * @param array|object|string $activity
     * @return \ActivityPub\Server\Activity\ActivityHandler
     */
    public function post($activity)
    {
        // JSON payload
        if (is_string($activity)) {
            $activity = Util::decodeJson($activity);
        }

        // Normalize
        if (!($activity instanceof AbstractObject)) {
            $activity = Type::create($activity);
        }

        // Log
        $this->getServer()->logger()->debug(
            $this->name . ':' . __METHOD__ . '(starting)', 
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
            $this->name . ':' . __METHOD__ . '(posted)', 
            $activity->toArray()
        );

        // Return a standard HTTP Response
        return $handler->handle()->getResponse();
        return new Response([
            'code'      => 201,
            'location'  => $activity->id
        ]);
    }
}
