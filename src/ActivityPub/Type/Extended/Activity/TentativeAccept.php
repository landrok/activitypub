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

/**
 * \ActivityPub\Type\Extended\Activity\TentativeAccept is an 
 * implementation of one of the Activity Streams Extended Types.
 *
 * A specialization of Accept indicating that the acceptance is 
 * tentative. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-tentativeaccept
 */
class TentativeAccept extends Accept
{
    /**
     * @var string
     */
    protected $type = 'TentativeAccept';
}
