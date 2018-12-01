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
 * \ActivityPub\Type\Extended\Activity\Accept is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor accepts the object. The target property can 
 * be used in certain circumstances to indicate the context into which 
 * the object has been accepted.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-accept
 */
class Accept extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Accept';
}
