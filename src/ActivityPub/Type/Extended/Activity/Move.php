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
 * \ActivityPub\Type\Extended\Activity\Move is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor has moved object from origin to target.
 * If the origin or target are not specified, either can be determined 
 * by context. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-move
 */
class Move extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Move';
}
