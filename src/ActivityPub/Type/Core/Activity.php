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
 * \ActivityPub\Type\Core\Activity is an implementation of one of the 
 * Activity Streams Core Types.
 *
 * Activity objects are specializations of the base Object type that
 * provide information about actions that have either already occurred, 
 * are in the process of occurring, or may occur in the future.
 *
 * @see https://www.w3.org/TR/activitystreams-core/#activities
 */
class Activity extends AbstractActivity
{
    /**
     * @var string
     */
    protected $type = 'Activity';

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
    protected $object;
}
