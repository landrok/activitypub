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
 * \ActivityPub\Type\Extended\Object\Document is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Represents an audio document of any kind. 
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-document
 */ 
class Document extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Document';
}
