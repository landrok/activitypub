<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type;
use ActivityPhp\Type\Ontology;
use Exception;
use PHPUnit\Framework\TestCase;
use ActivityPhpTest\MyCustomOntology;

abstract class MyNotwellDefinedOntology
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

class OntologyTest extends TestCase
{
    /**
     * Add a new ontology
     */
    public function testAddNewOne()
    {
        Ontology::clear();
        
        // Add and load this dialect
        Ontology::add('custom-ontology', MyCustomOntology::class);
        
        // Set this dialect property for one type
        $type = Type::create('Person', ['myOntologyField' => 1]);

        $this->assertEquals(
            1, 
            $type->myOntologyField
        );
        
        Ontology::clear();
	}

    /**
     * Should throw an Exception when ontology name is not allowed
     */
    public function testNotAllowedOntologyName()
    {
        $this->expectException(Exception::class);

        Ontology::clear();
        
        $ontology = 'MyClass';
        
        // Add and load this dialect
        Ontology::add('*', $ontology);
	}

    /**
     * Should throw an Exception when ontology class does not exist
     */
    public function testNotExistingOntology()
    {
        $this->expectException(Exception::class);

        Ontology::clear();
        
        $ontology = 'MyClass';
        
        // Add and load this dialect
        Ontology::add('cus-ontology', $ontology);
	}

    /**
     * Should throw an Exception when ontology cdoes not implement
     * its interface
     */
    public function testNotWellDefinedOntology()
    {
        $this->expectException(Exception::class);

        Ontology::clear();
        
        // Add and load this dialect
        Ontology::add('cus-ontology', MyNotwellDefinedOntology::class);
	}
}
