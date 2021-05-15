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

namespace ActivityPhp\Type\Extended\Actor;

use ActivityPhp\Type\Extended\AbstractActor;

/**
 * \ActivityPhp\Type\Extended\Actor\Group is an implementation of
 * one of the Activity Streams Extended Types.
 *
 * Represents a formal or informal collective of Actors.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-group
 */
class Group extends AbstractActor
{
    /**
     * @var string
     */
    protected $type = 'Group';
}
