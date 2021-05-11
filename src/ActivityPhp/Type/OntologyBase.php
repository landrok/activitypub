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
 * \ActivityPhp\Type\OntologyBase implements Ontology methods
 *
 * @since 0.4.0
 */
abstract class OntologyBase implements OntologyInterface
{
    /**
     * Get a specific dialects
     */
    public static function getDefinition(): array
    {
        return static::$definitions;
    }
}
