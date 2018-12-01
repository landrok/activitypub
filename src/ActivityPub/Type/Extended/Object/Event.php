<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Extended\Object;

use ActivityPub\Type\Core\ObjectType;

/**
 * \ActivityPub\Type\Extended\Object\Event is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Represents any kind of event.
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-event
 */ 
class Event extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Event';
}
