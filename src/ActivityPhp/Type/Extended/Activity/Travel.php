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

use ActivityPhp\Type\Core\IntransitiveActivity;

/**
 * \ActivityPhp\Type\Extended\Activity\Travel is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor is traveling to target from origin. Travel 
 * is an IntransitiveObject whose actor specifies the direct object.
 * If the target or origin are not specified, either can be determined 
 * by context. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-travel
 */
class Travel extends IntransitiveActivity
{
    /**
     * @var string
     */
    protected $type = 'Travel';
}
