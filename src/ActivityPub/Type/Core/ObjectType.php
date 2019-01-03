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
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
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
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
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
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $audience;

    /**
     * The content or textual representation of the Object encoded as a 
     * JSON string. By default, the value of content is HTML.
     * The mediaType property can be used in the object to indicate a 
     * different content type.
     * 
     * The content MAY be expressed using multiple language-tagged 
     * values. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-content
     * 
     * @var string|null
     */
    protected $content;

    /**
     * The context within which the object exists or an activity was 
     * performed.
     * The notion of "context" used is intentionally vague. 
     * The intended function is to serve as a means of grouping objects 
     * and activities that share a common originating context or 
     * purpose. An example could be all activities relating to a common 
     * project or event. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-context
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     */
    protected $context;

    /**
     * The content MAY be expressed using multiple language-tagged 
     * values. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-content
     * 
     * @var array|null
     */
    protected $contentMap;

    /**
     * A simple, human-readable, plain-text name for the object.
     * HTML markup MUST NOT be included.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-name
     * 
     * @var string|null xsd:string
     */
    protected $name;

    /**
     * The name MAY be expressed using multiple language-tagged values.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-name
     *
     * @var array|null rdf:langString
     */
    protected $nameMap;

    /**
     * The date and time describing the actual or expected ending time 
     * of the object.
     * When used with an Activity object, for instance, the endTime 
     * property specifies the moment the activity concluded or 
     * is expected to conclude.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-endtime
     * 
     * @var string|null
     */
    protected $endTime;

    /**
     * The entity (e.g. an application) that generated the object. 
     * 
     * @var string|null
     */
    protected $generator;

    /**
     * An entity that describes an icon for this object. 
     * The image should have an aspect ratio of one (horizontal) 
     * to one (vertical) and should be suitable for presentation 
     * at a small size. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-icon
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Image
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\Image[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $icon;

    /**
     * An entity that describes an image for this object.
     * Unlike the icon property, there are no aspect ratio 
     * or display size limitations assumed.  
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-image-term
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Image
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\Image[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $image;

    /**
     * One or more entities for which this object is considered a 
     * response.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-inreplyto
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $inReplyTo;

    /**
     * One or more physical or logical locations associated with the 
     * object.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-location
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $location;

    /**
     * An entity that provides a preview of this object. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-preview
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     */
    protected $preview;

    /**
     * The date and time at which the object was published  
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-published
     * 
     * @var string|null xsd:dateTime
     */
    protected $published;

    /**
     * A Collection containing objects considered to be responses to 
     * this object.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-replies
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Collection
     *    | \ActivityPub\Type\Core\Link
     */
    protected $replies;

    /**
     * The date and time describing the actual or expected starting time
     * of the object.
     * When used with an Activity object, for instance, the startTime 
     * property specifies the moment the activity began 
     * or is scheduled to begin. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-starttime
     * 
     * @var string|null xsd:dateTime
     */
    protected $startTime;

    /**
     * A natural language summarization of the object encoded as HTML.
     * Multiple language tagged summaries MAY be provided. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-summary
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     */
    protected $summary;

    /**
     * The content MAY be expressed using multiple language-tagged 
     * values.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-summary
     * 
     * @var array|null
     */
    protected $summaryMap;

    /**
     * One or more "tags" that have been associated with an objects.
     * A tag can be any kind of Object.
     * The key difference between attachment and tag is that the former
     * implies association by inclusion, while the latter implies 
     * associated by reference. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-tag
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $tag;

    /**
     * The date and time at which the object was updated  
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-updated
     * 
     * @var string|null xsd:dateTime
     */
    protected $updated;

    /**
     * One or more links to representations of the object.
     * 
     * @var null
     *    | string
     *    | string[]
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $url;

    /**
     * An entity considered to be part of the public primary audience 
     * of an Object 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-to
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $to;

    /**
     * An Object that is part of the private primary audience of this 
     * Object.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-bto
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $bto;

    /**
     * An Object that is part of the public secondary audience of this 
     * Object. 
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-cc
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $cc;

    /**
     * One or more Objects that are part of the private secondary
     * audience of this Object.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-bcc
     * 
     * @var string|null
     *    | null
     *    | \ActivityPub\Type\Core\ObjectType
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\ObjectType[]
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $bcc;

    /**
     * The MIME media type of the value of the content property.
     * If not specified, the content property is assumed to contain 
     * text/html content. 
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-mediatype
     *
     * @var string|null
     */
    protected $mediaType;

    /**
     * When the object describes a time-bound resource, such as an audio
     * or video, a meeting, etc, the duration property indicates the 
     * object's approximate duration.
     * The value MUST be expressed as an xsd:duration as defined by 
     * xmlschema11-2, section 3.3.6 (e.g. a period of 5 seconds is 
     * represented as "PT5S").
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-duration
     *  
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
