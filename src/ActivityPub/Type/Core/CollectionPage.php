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
 * \ActivityPub\Type\Core\CollectionPage is an implementation of one 
 * of the Activity Streams Core Types.
 *
 * Used to represent distinct subsets of items from a Collection.
 *
 * @see https://www.w3.org/TR/activitystreams-core/#paging
 */
class CollectionPage extends Collection
{
    /**
     * @var string
     */
    protected $type = 'CollectionPage';

    /**
     * @var string
     */
    protected $id;

    /**
     * Identifies the Collection to which a CollectionPage objects items 
     * belong. 
     *
     * @var string 
     */
    protected $partOf;

    /**
     * Indicates the next page of items. 
     * 
     * @var string|ActivityPub\Type\Core\Link 
     */
    protected $next;

    /**
     * Identifies the previous page of items. 
     * 
     * @var string|ActivityPub\Type\Core\Link
     */
    protected $prev;
}
