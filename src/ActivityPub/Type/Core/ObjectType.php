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
 * \ActivityPub\Type\Core\ObjectType is an implementation of one of the 
 * Activity Streams Core Types.
 * 
 * The Object is the primary base type for the Activity Streams 
 * vocabulary.
 * 
 * Note: Object is a reserved keyword in PHP. It has been suffixed with
 * 'Type' for this reason.
 * 
 * @see https://www.w3.org/TR/activitystreams-core/#object
 */ 
class ObjectType extends AbstractObject
{
    /**
     * The object's unique global identifier
     *    
     * @see https://www.w3.org/TR/activitypub/#obj-id
     * 
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type = 'Object';

    /**
     * A resource attached or related to an object that potentially 
     * requires special handling.
     * The intent is to provide a model that is at least semantically
     * similar to attachments in email.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-attachment
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Object
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\Object[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $attachment;

    /**
     * One or more entities to which this object is attributed. 
     * The attributed entities might not be Actors. For instance, an 
     * object might be attributed to the completion of another activity.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-attributedto
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Object
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\Object[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $attributedTo;

    /**
     * One or more entities that represent the total population of 
     * entities for which the object can considered to be relevant. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-audience
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Object
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\Object[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $audience;

    /**
     * @var string|null
     */
    protected $content;

    /**
     * @var string|null
     */
    protected $context;

    /**
     * @var string|null
     */
    protected $contentMap;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $nameMap;

    /**
     * @var string|null
     */
    protected $endTime;

    /**
     * @var string|null
     */
    protected $generator;

    /**
     * @var string|null
     */
    protected $icon;

    /**
     * @var string|null
     */
    protected $image;

    /**
     * @var string|null
     */
    protected $inReplyTo;

    /**
     * @var string|null
     */
    protected $location;

    /**
     * @var string|null
     */
    protected $preview;

    /**
     * @var string|null
     */
    protected $published;

    /**
     * @var string|null
     */
    protected $replies;

    /**
     * @var string|null
     */
    protected $startTime;

    /**
     * @var string|null
     */
    protected $summary;

    /**
     * @var string|null
     */
    protected $summaryMap;

    /**
     * @var string|null
     */
    protected $tag;

    /**
     * @var string|null
     */
    protected $updated;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var string|null
     */
    protected $to;

    /**
     * @var string|null
     */
    protected $bto;

    /**
     * @var string|null
     */
    protected $cc;

    /**
     * @var string|null
     */
    protected $bcc;

    /**
     * @var string|null
     */
    protected $mediaType;

    /**
     * @var string|null
     */
    protected $duration;

    /**
     * Intended to convey some sort of source from which the content 
     * markup was derived, as a form of provenance, or to support
     * future editing by clients.
     * 
     * @see https://www.w3.org/TR/activitypub/#source-property
     * 
     * @var \ActivityPub\Type\Core\ObjectType
     */
    protected $source;
}
