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
 * \ActivityPhp\Type\Extended\Activity\Delete is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor has deleted the object. If specified, the 
 * origin indicates the context from which the object was deleted.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-delete
 */
class Delete extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Delete';
}
