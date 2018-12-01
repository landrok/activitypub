<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type;

/**
 * \ActivityPub\Type\ValidatorInterface specifies methods that must be 
 * implemented for attribute (property) validation.
 */ 
interface ValidatorInterface
{
    public function validate($value, $container);
}
