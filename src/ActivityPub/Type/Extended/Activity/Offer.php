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

use ActivityPub\Type\Core\Activity;

/**
 * \ActivityPub\Type\Extended\Activity\Offer is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor is offering the object. If specified, the 
 * target indicates the entity to which the object is being offered. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-offer
 */
class Offer extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Offer';
}
