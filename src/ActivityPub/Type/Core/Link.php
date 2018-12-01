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
 * \ActivityPub\Type\Core\Link is an implementation of one of the 
 * Activity Streams Core Types.
 * 
 * A Link describes a qualified, indirect reference to another resource. 
 * The properties of the Link object are not the properties of the 
 * referenced resource, but are provided as hints for rendering agents 
 * to understand how to make use of the resource.
 * 
 * @see https://www.w3.org/TR/activitystreams-core/#link
 */ 
class Link extends AbstractObject
{
    /**
     * @var string
     */
    protected $type = 'Link';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $hreflang;

    /**
     * @var string
     */
    protected $mediaType;

    /**
     * @var string
     */
    protected $rel;

    /**
     * @var string
     */
    protected $height;

    /**
     * @var string
     */
    protected $width;
}
