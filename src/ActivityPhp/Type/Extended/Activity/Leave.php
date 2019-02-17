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
 * \ActivityPhp\Type\Extended\Activity\Leave is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor has left the object.
 * The target and origin typically have no meaning. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-leave
 */
class Leave extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Leave';
}
