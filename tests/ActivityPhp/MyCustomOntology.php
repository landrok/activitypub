<?php

namespace ActivityPhpTest;

use ActivityPhp\Type\OntologyBase ;

class MyCustomOntology extends OntologyBase
{
    /**
     * A definition of custom's ontology to overload Activity 
     * Streams vocabulary.
     * 
     * @var array
     */
    protected static $definitions = [
        'Person|Group' => ['myOntologyField'],
    ];
}
