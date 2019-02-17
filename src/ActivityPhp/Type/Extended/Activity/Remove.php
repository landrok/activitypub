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
 * \ActivityPhp\Type\Extended\Activity\Remove is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor is removing the object.
 * If specified, the origin indicates the context from which the object 
 * is being removed. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-remove
 */
class Remove extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Remove';
}
