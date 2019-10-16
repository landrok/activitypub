<?php

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
     * 
     * @var array
     */
    public static function getDefinition()
    {
        return static::$definitions;    
    }
}
