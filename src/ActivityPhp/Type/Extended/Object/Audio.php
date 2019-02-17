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

/**
 * \ActivityPhp\Type\Extended\Object\Audio is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Represents a document of any kind. 
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-audio
 */ 
class Audio extends Document
{
    /**
     * @var string
     */
    protected $type = 'Audio';
}
