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
 * \ActivityPub\Type\Extended\Object\Article is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Represents any kind of multi-paragraph written work. 
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-article
 */ 
class Article extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Article';
}
