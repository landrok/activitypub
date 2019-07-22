<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type;
use ActivityPhp\Type\Dialect;
use Exception;
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
     */
    public function testAddLoadUnloadDialect()
    {
        $this->expectException(Exception::class);

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
     */
    public function testAddLoadUnloadAllDialects()
    {
        $this->expectException(Exception::class);

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
     */
    public function testWrongPropertyDefinition()
    {
        $this->expectException(Exception::class);

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
     */
    public function testUsingNotLoadedDialect()
    {
        $this->expectException(Exception::class);

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
     */
    public function testLoadingUndefinedDialect()
    {
        $this->expectException(Exception::class);

        Dialect::clear();
        
        Dialect::load('mydialect');
	} 

    /**
     * Throw an Exception when trying to use a type that has been 
     * unloaded
     */
    public function testUsingAnUnloadedType()
    {
        $this->expectException(Exception::class);

        Dialect::clear();
        
        $dialect = [
            // A new type
            'MyDialectType' => [
                'myDialectField'
            ],
        ];
        
        // Add and load
        Dialect::add('mydialect', $dialect);
        Dialect::unload('mydialect');
        Type::create('MyDialectType');
	} 

    /**
     * Create a new callable type with dialect
     */
    public function testCreateANewDialectType()
    {
        Dialect::clear();
        
        $dialect = [
            // A new type
            'MyDialectType' => [
                'myDialectField'
            ],
        ];
        
        // Add and load
        Dialect::add('mydialect', $dialect);
        $type = Type::create('MyDialectType', ['myDialectField' => 1]);
        
        $this->assertEquals(
            1, 
            $type->myDialectField
        );
	} 
}
