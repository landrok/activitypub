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

/**
 * \ActivityPub\Type\Extended\Object\Video is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Represents a video document of any kind. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-video
 */ 
class Video extends Document
{
    /**
     * @var string
     */
    protected $type = 'Video';
}
