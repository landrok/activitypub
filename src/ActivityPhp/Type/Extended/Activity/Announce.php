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

namespace ActivityPhp\Type\Extended\Activity;

use ActivityPhp\Type\Core\Activity;

/**
 * \ActivityPhp\Type\Extended\Activity\Announce is an implementation of
 * one of the Activity Streams Extended Types.
 *
 * Indicates that the actor is calling the target's attention the
 * object.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-announce
 */
class Announce extends Activity
{
    /**
     * @var string
     */
    protected $type = 'Announce';
}
