<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Extended\Object;

use ActivityPhp\Type\Core\ObjectType;

/**
 * \ActivityPhp\Type\Extended\Object\Event is an implementation of 
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
