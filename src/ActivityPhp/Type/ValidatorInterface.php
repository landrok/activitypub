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

namespace ActivityPhp\Type;

/**
 * \ActivityPhp\Type\ValidatorInterface specifies methods that must be
 * implemented for attribute (property) validation.
 */
interface ValidatorInterface
{
    public function validate($value, $container): bool;
}
