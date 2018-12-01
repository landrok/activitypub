<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Extended\Actor;

use ActivityPub\Type\Extended\AbstractActor;

/**
 * \ActivityPub\Type\Extended\Actor\Application is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Describes a software application. 
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-application
 */ 
class Application extends AbstractActor
{
    /**
     * @var string
     */
    protected $type = 'Application';
}
