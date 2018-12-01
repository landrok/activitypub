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
 * \ActivityPub\Type\Extended\Object\Tombstone is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * A Tombstone represents a content object that has been deleted. It can
 * be used in Collections to signify that there used to be an object at 
 * this position, but it has been deleted.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-deleted
 */ 
class Tombstone extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Tombstone';

    /**
     * The type of the object that was deleted. 
     * 
     * @var \ActivityPub\Type\Core\ObjectType
     */
    protected $formerType;

    /**
     * A timestamp for when the object was deleted. 
     * 
     * @var \DateTime
     */
    protected $deleted;
}
