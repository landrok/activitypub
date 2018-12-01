<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Extended\Object;

use ActivityPub\Type\Core\ObjectType;

/**
 * \ActivityPub\Type\Extended\Object\Profile is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * A Profile is a content object that describes another Object, 
 * typically used to describe Actor Type objects.
 * The describes property is used to reference the object being 
 * described by the profile.
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-profile
 */ 
class Profile extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Profile';

    /**
     * Identify the object described by the Profile. 
     * 
     * @var \ActivityPub\Type\Core\ObjectType
     */
    protected $describes;
}
