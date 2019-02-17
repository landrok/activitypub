<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Extended;

use ActivityPhp\Type\Core\ObjectType;

/**
 * \ActivityPhp\Type\Extended\AbstractActor is an abstract class that
 * provides dedicated Actor's properties
 */ 
abstract class AbstractActor extends ObjectType
{
    /**
     * A reference to an ActivityStreams OrderedCollection comprised of 
     * all the messages received by the actor.
     * 
     * @see https://www.w3.org/TR/activitypub/#inbox
     * 
     * @var null
     *    | \ActivityPhp\Type\Core\OrderedCollection
     *    | \ActivityPhp\Type\Core\OrderedCollectionPage
     */
    protected $inbox;

    /**
     * A reference to an ActivityStreams OrderedCollection comprised of 
     * all the messages produced by the actor.
     * 
     * @see https://www.w3.org/TR/activitypub/#outbox
     * 
     * @var null
     *    | \ActivityPhp\Type\Core\OrderedCollection
     *    | \ActivityPhp\Type\Core\OrderedCollectionPage
     */
    protected $outbox;

    /**
     * A link to an ActivityStreams collection of the actors that this 
     * actor is following.
     * 
     * @see https://www.w3.org/TR/activitypub/#following
     * 
     * @var string
     */
    protected $following;

    /**
     * A link to an ActivityStreams collection of the actors that 
     * follow this actor.
     * 
     * @see https://www.w3.org/TR/activitypub/#followers
     * 
     * @var string
     */
    protected $followers;

    /**
     * A link to an ActivityStreams collection of objects this actor has 
     * liked.
     * 
     * @see https://www.w3.org/TR/activitypub/#liked
     * 
     * @var string
     */
    protected $liked;

    /**
     * A list of supplementary Collections which may be of interest. 
     * 
     * @see https://www.w3.org/TR/activitypub/#streams-property
     *
     * @var array
     */
    protected $streams = [];

    /**
     * A short username which may be used to refer to the actor, with no
     * uniqueness guarantees.
     * 
     * @see https://www.w3.org/TR/activitypub/#preferredUsername  
     * 
     * @var null|string
     */
    protected $preferredUsername;

    /**
     * A json object which maps additional typically server/domain-wide
     * endpoints which may be useful either for this actor or someone 
     * referencing this actor. This mapping may be nested inside the 
     * actor document as the value or may be a link to a JSON-LD 
     * document with these properties. 
     * 
     * @see https://www.w3.org/TR/activitypub/#endpoints
     *  
     * @var null|string|array
     */
    protected $endpoints;
}
