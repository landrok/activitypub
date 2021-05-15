<?php

declare(strict_types=1);

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Core;

/**
 * \ActivityPhp\Type\Core\AbstractActivity implements only common
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
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-actor
     *
     * @var string
     *    | \ActivityPhp\Type\Extended\AbstractActor
     *    | array<Actor>
     *    | array<Link>
     *    | Link
     */
    protected $actor;

    /**
     * The indirect object, or target, of the activity.
     * The precise meaning of the target is largely dependent on the
     * type of action being described but will often be the object of
     * the English preposition "to".
     * For instance, in the activity "John added a movie to his
     * wishlist", the target of the activity is John's wishlist.
     * An activity can have more than one target.
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-target
     *
     * @var string
     *    | ObjectType
     *    | array<ObjectType>
     *    | Link
     *    | array<Link>
     */
    protected $target;

    /**
     * Describes the result of the activity.
     * For instance, if a particular action results in the creation of
     * a new resource, the result property can be used to describe
     * that new resource.
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-result
     *
     * @var string
     *    | ObjectType
     *    | Link
     *    | null
     */
    protected $result;

    /**
     * An indirect object of the activity from which the
     * activity is directed.
     * The precise meaning of the origin is the object of the English
     * preposition "from".
     * For instance, in the activity "John moved an item to List B
     * from List A", the origin of the activity is "List A".
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-origin
     *
     * @var string
     *    | ObjectType
     *    | Link
     *    | null
     */
    protected $origin;

    /**
     * One or more objects used (or to be used) in the completion of an
     * Activity.
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-instrument
     *
     * @var string
     *    | ObjectType
     *    | Link
     *    | null
     */
    protected $instrument;
}
