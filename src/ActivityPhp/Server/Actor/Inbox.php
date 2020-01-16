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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        parent::__construct($actor, $server);
    }

    /**
     * Post a message to current actor
     * 
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function post(ServerRequestInterface $request)
    {
         try {
            // Check accept header
            Helper::validateAcceptHeader(
                $request->getHeaderLine('accept'),
                true
            );

            // Check current actor can post

            // Get content
            $payload = Util::decodeJson($request->getBody()->getContents());

            // Cast as an ActivityStreams type
            $activity = Type::create($payload);

        } catch (Exception $exception) {
            $response = $this->server->getResponseFactory()->createResponse(400);
            $response->getBody()->write($exception->getMessage());

            return $response;
        }

        $httpSignature = new HttpSignature($this->server);
        if ($httpSignature->verify($request)) {
            return $this->server->getResponseFactory()->createResponse(201);
        }

        return $this->server->getResponseFactory()->createResponse(403);
    }
}
