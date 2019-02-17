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

use ActivityPhp\Type\Core\Activity;

/**
 * \ActivityPhp\Type\Extended\Activity\Undo is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor is undoing the object. In most cases, the 
 * object will be an Activity describing some previously performed 
 * action (for instance, a person may have previously "liked" an article
 * but, for whatever reason, might choose to undo that like at some 
 * later point in time).
 *
 * The target and origin typically have no defined meaning. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-undo
 */
class Undo extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Undo';
}
