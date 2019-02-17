<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Activity;

use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract class for all activity handlers
 */ 
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    private $response;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * Get HTTP response instance
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
