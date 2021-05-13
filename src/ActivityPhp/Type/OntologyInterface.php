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
 * \ActivityPhp\Type\OntologyInterface specifies methods that must be
 * implemented for ontology definitions
 */
interface OntologyInterface
{
    /**
     * Get an array of dialect definitions
     */
    public static function getDefinition(): array;
}
