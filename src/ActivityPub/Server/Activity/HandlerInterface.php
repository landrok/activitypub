<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Server\Activity;

/**
 * Interface for all activity handlers
 */ 
interface HandlerInterface
{
    /**
     * @return self
     */
    public function handle();

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse();
}
