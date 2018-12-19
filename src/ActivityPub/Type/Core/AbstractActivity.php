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

/**
 * \ActivityPub\Type\Core\AbstractActivity implements only common
 * attributes between Activity and IntransitiveActivity.
 *
 * It SHOULD NOT be used as if.
 * 
 * Please use IntransitiveActivity or Activity instead.
 * 
 * @see https://www.w3.org/TR/activitystreams-core/#activities
 * @see https://www.w3.org/TR/activitystreams-core/#intransitiveactivities
 */
abstract class AbstractActivity extends ObjectType
{
    /**
     * @var string
     */
    protected $id;

    /**
     * Describes one or more entities that either performed or are 
     * expected to perform the activity.
     * Any single activity can have multiple actors.
     * The actor MAY be specified using an indirect Link.
     *
     * @var string
     *    | \ActivityPub\Type\Core\Actor
     *    | \ActivityPub\Type\Core\Actor[]
     *    | \ActivityPub\Type\Core\Link
     *    | \ActivityPub\Type\Core\Link[]
     */
    protected $actor;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $result;

    /**
     * Describes an indirect object of the activity from which the 
     * activity is directed.
     * The precise meaning of the origin is the object of the English 
     * preposition "from".
     * For instance, in the activity "John moved an item to List B 
     * from List A", the origin of the activity is "List A".
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-origin
     * 
     * @var string
     *    | null
     *    | \ActivityPub\Type\Core\Object
     *    | \ActivityPub\Type\Core\Link
     */
    protected $origin;

    /**
     * @var string
     */
    protected $instrument;
}
