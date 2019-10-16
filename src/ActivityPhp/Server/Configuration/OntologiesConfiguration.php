<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Configuration;

use ActivityPhp\Type\Ontology;
use Exception;

/**
 * Ontologies configuration stack
 */ 
class OntologiesConfiguration extends AbstractConfiguration
{
    /**
     * Dispatch configuration parameters
     * 
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $ontology => $class) {
            // It must be the name of ontology
            if (is_string($ontology)) {
                // It's an external ontology
                if (!is_null($class)) {
                    Ontology::add($ontology, $class);
                }
            // internal : it's an integer key
            } else {
                Ontology::load($class); 
            }
        }    
    }
}
