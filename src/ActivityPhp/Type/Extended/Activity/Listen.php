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
 * \ActivityPhp\Type\Extended\Activity\Listen is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor has listened to the object.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-listen
 */
class Listen extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Listen';
}
