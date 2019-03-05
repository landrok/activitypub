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
use ActivityPhp\Server\Helper;
use ActivityPhp\Server\Http\HttpSignature;
use ActivityPhp\Type;
use ActivityPhp\Type\Util;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            $actor->get('preferredUsername') . ':' . __METHOD__
        );
        parent::__construct($actor, $server);
    }

    /**
     * Post a message to current actor
     * 
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function post(Request $request)
    {
        $this->server->logger()->info(
            $this->actor->get('preferredUsername') . ':' . __METHOD__
        );

        try {
            // Check accept header
            if (!Helper::validateAcceptHeader(
                    $request->headers->get('accept')
                )
            ) {
                throw new Exception(
                    sprintf(
                        "HTTP Accept header error. Given: '%s'",
                        $request->headers->get('accept')
                    )
                );
            }

            // Check current actor can post
            
            
            // Get content
            $payload = Util::decodeJson(
                (string)$request->getContent()
            );

            // Cast as an ActivityStreams type
            $activity = Type::create($payload);

        } catch (Exception $exception) {
            $this->getServer()->logger()->error(
                $this->actor->get()->preferredUsername. ':' . __METHOD__, [
                    $exception->getMessage()
                ]
            );

            return new Response('', 400);
        }

        $httpSignature = new HttpSignature($this->server);
        if ($httpSignature->verify($request)) {
            return new Response('', 201);
        }

        return new Response('', 403);
    }
}
