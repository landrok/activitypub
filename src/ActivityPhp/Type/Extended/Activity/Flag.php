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
 * \ActivityPhp\Type\Extended\Activity\Flag is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor is "flagging" the object. Flagging is 
 * defined in the sense common to many social platforms as reporting 
 * content as being inappropriate for any number of reasons.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-flag
 */
class Flag extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Flag';
}
