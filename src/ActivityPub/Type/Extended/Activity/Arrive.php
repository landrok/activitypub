<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Extended\Activity;

use ActivityPub\Type\Core\IntransitiveActivity;

/**
 * \ActivityPub\Type\Extended\Activity\Arrive is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * An IntransitiveActivity that indicates that the actor has arrived at 
 * the location. The origin can be used to identify the context from 
 * which the actor originated.
 * The target typically has no defined meaning.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-arrive
 */
class Arrive extends IntransitiveActivity
{
    /**
     * @var string
     */
    protected $type = 'Arrive';
}
