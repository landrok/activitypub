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
 * \ActivityPub\Type\Extended\Object\Relationship is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Describes a relationship between two individuals. 
 * $subject (source) has a $relationship with $object (target)
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-relationship
 */ 
class Relationship extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Relationship';

    /**
     * One of the connected individuals.
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-subject
     *
     * @var null
     *     | string
     *     | \ActivityPub\Type\Core\ObjectType
     *     | \ActivityPub\Type\Core\Link
     */
    protected $subject;

    /**
     * The entity to which the subject is related.  
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-object-term
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Object
     *    | \ActivityPub\Type\Core\Link
     */
    protected $object;

    /**
     * Type of relationship
     * 
     * @var string URL
     */
    protected $relationship;
}
