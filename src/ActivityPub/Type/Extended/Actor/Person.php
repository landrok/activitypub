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
 * \ActivityPub\Type\Extended\Actor\Person is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Represents an individual person. 
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-person
 */ 
class Person extends AbstractActor
{
    /**
     * @var string
     */
    protected $type = 'Person';
}
