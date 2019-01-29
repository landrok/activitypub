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
use ActivityPub\Server\Actor;

/**
 * A server-side inbox
 */ 
class Inbox extends AbstractBox
{
    /**
     * Inbox constructor
     * 
     * @param  \ActivityPub\Server\Actor $actor An actor
     * @param  \ActivityPub\Server $server
     */
    public function __construct(Actor $actor, Server $server)
    {
        $server->logger()->info(
            $actor->getType()->preferredUsername . ':' . __METHOD__
        );
        parent::__construct($actor, $server);
    }
}
