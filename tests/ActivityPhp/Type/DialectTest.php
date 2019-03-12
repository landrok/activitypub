<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type;
use ActivityPhp\Type\Dialect;
use PHPUnit\Framework\TestCase;

class DialectTest extends TestCase
{
    /**
     * Extend one type with one property
     */
    public function testExtendOneTypeWithOneProperty()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person' => [
                'myDialectField'
            ],
        ];
        
        // Add and load this dialect
        Dialect::add('mydialect', $dialect);
        
        // Set this dialect property for one type
        $type = Type::create('Person', ['myDialectField' => 1]);

        $this->assertEquals(
            1, 
            $type->myDialectField
        );
	}

    /**
     * Define and load a property for 2 dialect
     */
    public function testExtendWithTwoDialects()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person' => [
                'myDialectField'
            ],
        ];
        
        // Add and load these dialect
        Dialect::add('myDialect', $dialect);
        Dialect::add('mySecondDialect', $dialect);
        
        // Set this dialect property for one type
        $type = Type::create('Person', ['myDialectField' => 1]);

        $this->assertEquals(
            1, 
            $type->myDialectField
        );
	}

    /**
     * Extend 2 types type with one property
     */
    public function testExtendTwoTypeWithOneProperty()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person|Application' => [
                'myDialectField'
            ],
        ];
        
        // Add and load this dialect
        Dialect::add('mydialect', $dialect);
        
        // Set this dialect property for one type
        $person = Type::create('Person', ['myDialectField' => 1]);
        $application = Type::create('Application', ['myDialectField' => 2]);

        $this->assertEquals(
            1, 
            $person->myDialectField
        );
        $this->assertEquals(
            2, 
            $application->myDialectField
        );
	}
    
    /**
     * Add and load a dialect in 2 steps
     */
    public function testAddThenLoadDialect()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person|Application' => [
                'myDialectField'
            ],
        ];
        
        // Simply add this dialect
        Dialect::add('mydialect', $dialect, false);

        // Load it
        Dialect::load('mydialect');
        
        // Set this dialect property for one type
        $person = Type::create('Person', ['myDialectField' => 1]);

        $this->assertEquals(
            1, 
            $person->myDialectField
        );
	} 

    /**
     * Add and load all dialects in 2 steps
     */
    public function testAddThenLoadAllDialect()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person|Application' => [
                'myDialectField'
            ],
        ];
        
        // Simply add this dialect
        Dialect::add('mydialect', $dialect, false);

        // Load it
        Dialect::load('*');
        
        // Set this dialect property for one type
        $person = Type::create('Person', ['myDialectField' => 1]);

        $this->assertEquals(
            1, 
            $person->myDialectField
        );
	} 

    /**
     * Should throw an Exception when using a dialect that has
     * been defined and loaded, then unloaded
     * 
     * @expectedException \Exception
     */
    public function testAddLoadUnloadDialect()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person|Application' => [
                'myDialectField'
            ],
        ];
        
        // Add and load this dialect
        Dialect::add('mydialect', $dialect);
        Dialect::unload('mydialect');
        
        // Set this dialect property for one type
        $person = Type::create('Person', ['myDialectField' => 1]);
	} 

    /**
     * Should throw an Exception when using a dialect that has
     * been defined and loaded, then unloaded with *
     * 
     * @expectedException \Exception
     */
    public function testAddLoadUnloadAllDialects()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person|Application' => [
                'myDialectField'
            ],
        ];
        
        // Add and load this dialect
        Dialect::add('mydialect', $dialect);
        Dialect::unload('*');
        
        // Set this dialect property for one type
        $person = Type::create('Person', ['myDialectField' => 1]);
	} 

    /**
     * Should throw an Exception when a property has a wrong definition
     * 
     * @expectedException \Exception
     */
    public function testWrongPropertyDefinition()
    {
        Dialect::clear();
        
        $dialect = [
            // Add a wrong definition (properties must be an array
            'Person|Application' => 
                'myDialectField'
            ,
        ];
        
        // Add and load this dialect
        Dialect::add('mydialect', $dialect);
	} 

    /**
     * Should throw an Exception when using a define dialect that has
     * not been loaded
     * 
     * @expectedException \Exception
     */
    public function testUsingNotLoadedDialect()
    {
        Dialect::clear();
        
        $dialect = [
            // Add fields to one type
            'Person|Application' => [
                'myDialectField'
            ],
        ];
        
        // Simply add this dialect
        Dialect::add('mydialect', $dialect, false);
        
        // Set this dialect property for one type
        $person = Type::create('Person', ['myDialectField' => 1]);
	} 

    /**
     * Should throw an Exception when trying to load an undefined
     * dialect
     * 
     * @expectedException \Exception
     */
    public function testLoadingUndefinedDialect()
    {
        Dialect::clear();
        
        Dialect::load('mydialect');
	}  
}
