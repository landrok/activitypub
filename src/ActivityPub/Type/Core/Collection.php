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
 * \ActivityPub\Type\Core\Collection is an implementation of one of the 
 * Activity Streams Core Types.
 *
 * Collection objects are a specialization of the base Object that serve
 * as a container for other Objects or Links.
 *
 * @see https://www.w3.org/TR/activitystreams-core/#collections
 */
class Collection extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Collection';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $totalItems;

    /**
     * @var string
     */
    protected $current;

    /**
     * @var string
     */
    protected $first;

    /**
     * @var string
     */
    protected $last;

    /**
     * @var array
     */
    protected $items = [];
}
