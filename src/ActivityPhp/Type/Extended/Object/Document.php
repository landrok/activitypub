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
 * \ActivityPhp\Type\Extended\Object\Document is an implementation of 
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
