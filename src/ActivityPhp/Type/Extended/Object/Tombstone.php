<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Extended\Object;

use ActivityPhp\Type\Core\ObjectType;

/**
 * \ActivityPhp\Type\Extended\Object\Tombstone is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * A Tombstone represents a content object that has been deleted. It can
 * be used in Collections to signify that there used to be an object at 
 * this position, but it has been deleted.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-tombstone
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
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-formertype
     * 
     * @var null|string
     */
    protected $formerType;

    /**
     * A timestamp for when the object was deleted. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-deleted
     * 
     * @var null|string xsd:dateTime formatted
     */
    protected $deleted;
}
