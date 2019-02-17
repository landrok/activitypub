<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Extended\Object;

use ActivityPhp\Type\Core\Link;

/**
 * \ActivityPhp\Type\Extended\Object\Mention is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * A specialized Link that represents an @mention. 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-mention
 */ 
class Mention extends Link
{
    /**
     * @var string
     */
    protected $type = 'Mention';
}
