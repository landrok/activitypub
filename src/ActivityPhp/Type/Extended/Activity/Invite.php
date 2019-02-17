<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Extended\Activity;

/**
 * \ActivityPhp\Type\Extended\Activity\Invite is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * A specialization of Offer in which the actor is extending an 
 * invitation for the object to the target.  
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-invite
 */
class Invite extends Offer
{
    /**
     * @var string
     */
    protected $type = 'Invite';
}
