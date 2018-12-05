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
     * The target resource pointed to by a Link. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-href
     * 
     * @var null|string
     */
    protected $href;

    /**
     * Hints as to the language used by the target resource.
     * Value MUST be a BCP47 Language-Tag. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-hreflang
     * 
     * @var null|string
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
     * Specifies a hint as to the rendering height 
     * in device-independentpixels of the linked resource
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-height
     * 
     * @var null|int A non negative integer
     */
    protected $height;

    /**
     * An entity that provides a preview of this link. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-preview
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Object
     *    | \ActivityPub\Type\Core\Link
     */
    protected $preview;

    /**
     * On a Link, specifies a hint as to the rendering width in 
     * device-independent pixels of the linked resource.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-width
     * 
     * @var null|int A non negative integer
     */
    protected $width;
}
