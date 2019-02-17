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
use ActivityPhp\Server\Actor;

/**
 * A server-side inbox
 */ 
class Inbox extends AbstractBox
{
    /**
     * Inbox constructor
     * 
     * @param  \ActivityPhp\Server\Actor $actor An actor
     * @param  \ActivityPhp\Server $server
     */
    public function __construct(Actor $actor, Server $server)
    {
        $server->logger()->info(
            $actor->getType()->preferredUsername . ':' . __METHOD__
        );
        parent::__construct($actor, $server);
    }
}
