<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Core;

use ActivityPub\Type\AbstractObject;

/**
 * \ActivityPub\Type\Core\IntransitiveActivity is an implementation of 
 * one of the Activity Streams Core Types.
 *
 * IntransitiveActivity objects are specializations of the Activity type
 * that represent intransitive actions. IntransitiveActivity objects do
 * not have an object property.
 *
 * @see https://www.w3.org/TR/activitystreams-core/#intransitiveactivities
 */
class IntransitiveActivity extends Activity
{
    /**
     * @var string
     */
    protected $type = 'IntransitiveActivity';

    /**
     * Instanciate
     */
    public function __construct()
    {
        // Inherits all properties from Activity except object.
        unset($this->object);
    }
}
