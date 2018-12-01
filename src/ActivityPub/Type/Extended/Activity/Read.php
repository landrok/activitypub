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
 * \ActivityPub\Type\Extended\Activity\Read is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor has read the object.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-read
 */
class Read extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Read';
}
