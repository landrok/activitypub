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
 * \ActivityPub\Type\Extended\Activity\Add is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor has added the object to the target. If the 
 * target property is not explicitly specified, the target would need to 
 * be determined implicitly by context. The origin can be used to 
 * identify the context from which the object originated.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-add
 */
class Add extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Add';
}
