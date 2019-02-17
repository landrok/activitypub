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
 * \ActivityPhp\Type\Extended\Activity\Follow is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor is "following" the object. Following is 
 * defined in the sense typically used within Social systems in which 
 * the actor is interested in any activity performed by or on the 
 * object. 
 * The target and origin typically have no defined meaning. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-follow
 */
class Follow extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Follow';
}
