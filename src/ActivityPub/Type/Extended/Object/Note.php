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
 * \ActivityPub\Type\Extended\Object\Note is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Represents a short written work typically less than a single 
 * paragraph in length.
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-note
 */ 
class Note extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Note';
}
