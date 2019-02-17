<?php

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
 * \ActivityPhp\Type\Core\OrderedCollection is an implementation of one 
 * of the Activity Streams Core Types.
 *
 * The OrderedCollectionPage type extends from both CollectionPage and 
 * OrderedCollection. In addition to the properties inherited from each
 * of those, the OrderedCollectionPage may contain an additional 
 * startIndex property whose value indicates the relative index position
 * of the first item contained by the page within the OrderedCollection 
 * to which the page belongs.
 *
 * @see https://www.w3.org/TR/activitystreams-core/#paging
 */
class OrderedCollectionPage extends CollectionPage
{
    /**
     * @var string
     */
    protected $type = 'OrderedCollectionPage';

    /**
     * A non-negative integer value identifying the relative position 
     * within the logical view of a strictly ordered collection.
     *
     * @var int
     */
    protected $startIndex;
}
