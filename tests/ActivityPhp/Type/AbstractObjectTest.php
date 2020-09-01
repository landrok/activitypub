<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type;
use ActivityPhpTest\MyCustomType;
use Exception;
use PHPUnit\Framework\TestCase;

class AbstractObjectTest extends TestCase
{
    /**
     * Test valid setters
     */
    public function testValidSetters()
    {
        $type = Type::create('ObjectType');

        $value = 'http://example1.com';
        $type->id = $value;
        $this->assertEquals($value, $type->id);

        $value = 'http://example2.com';
        $type->set('id', $value);
        $this->assertEquals($value, $type->id);

        $value = 'http://example3.com';
        $type->setId($value);
        $this->assertEquals($value, $type->id);
    }

    /**
     * Test valid getters
     */
    public function testValidGetters()
    {
        $type = Type::create('ObjectType');

        $value = 'http://example1.com';
        $type->id = $value;
        $this->assertEquals($value, $type->id);

        $value = 'http://example2.com';
        $type->set('id', $value);
        $this->assertEquals($value, $type->getId());

        $value = 'http://example3.com';
        $type->setId($value);
        $this->assertEquals($value, $type->get('id'));
    }

    /**
     * Getting a non defined should throw an exception
     */
    public function testGetEmptyProperty()
    {
        $this->expectException(Exception::class);

        $object = Type::create('ObjectType');
        $object->myCustomAttribute;
    }

    /**
     * Setting without argument should throw an exception
     */
    public function testSetWithNoArgument()
    {
        $this->expectException(Exception::class);

        $object = Type::create('ObjectType');
        $object->setMyCustomAttribute();
    }

    /**
     * Call an undefined method
     */
    public function testCallUndefinedMethod()
    {
        $this->expectException(Exception::class);

        $object = Type::create('ObjectType');
        $object->illegalCall();
    }

    /**
     * tests getProperties() method
     */
    public function testGetProperties()
    {	
        $expected = [
            'type',
            'id',
            'name',
            'nameMap',
            'href',
            'hreflang',
            'mediaType',
            'rel',
            'height',
            'preview',
            'width'
        ];

        $this->assertEquals(
            $expected, 
            Type::create('Link')->getProperties()
        );
    }

    /**
     * tests toArray() method
     */
    public function testToArrayWithEmptyProperties()
    {	
        $expected = [
            'type' => 'Link',
            'name' => 'An example',
            'href' => 'http://example.com',
        ];

        $this->assertEquals(
            $expected, 
            Type::create('Link', $expected)->toArray()
        );
    }

    /**
     * Try to set a property without any validator
     */
    public function testToSetFreeProperty()
    {
        Type::add('MyCustomType', MyCustomType::class);

        $expected = [
            'type' => 'MyCustomType',
            'customFreeProperty' => 'Free value',
            'streams' => [],
        ];

        $this->assertEquals(
            $expected, 
            Type::create('MyCustomType', $expected)->toArray()
        );
    }

    /**
     * tests toArray() method
     */
    public function testToArrayWithSomePropertiesSet()
    {	
        $expected = [
            'type' => 'Link',
        ];

        $this->assertEquals(
            $expected, 
            Type::create('Link')->toArray()
        );
    }


    /**
     * tests toJson() method
     */
    public function testToJson()
    {	
        $expected = [
            'type' => 'Link',
        ];

        $this->assertEquals(
            '{"type":"Link"}',
            Type::create('Link')->toJson()
        );
    }

    /**
     * tests toJson() method
     */
    public function testToJsonWithSomeProperties()
    {	
        $expected = [
            'type' => 'Link',
            'name' => 'An example',
            'href' => 'http://example.com',
        ];

        $this->assertEquals(
            '{"type":"Link","name":"An example","href":"http:\/\/example.com"}',
            Type::create($expected)->toJson()
        );
    }

    /**
     * tests toJson() method and PHP JSON options
     */
    public function testToJsonWithPhpOptions()
    {	
        $expected = [
            'type' => 'Link',
            'name' => 'An example',
            'href' => 'http://example.com',
        ];

        $this->assertEquals(
            '{' . "\n" .
'    "type": "Link",' . "\n" .
'    "name": "An example",' . "\n" .
'    "href": "http:\/\/example.com"' . "\n" .
'}',
            Type::create($expected)->toJson(JSON_PRETTY_PRINT)
        );
    }

    /**
     * Tests has() method throws an Exception with $strict=true
     */
    public function testHasStrictCheck()
    {
        $this->expectException(Exception::class);

        $object = Type::create('ObjectType');
        $object->has('UndefinedProperty', true);
    }

    /**
     * Tests has() method returns false with $strict=false
     */
    public function testHasCheck()
    {
        $object = Type::create('ObjectType');
        
        $this->assertEquals(
            false,
            $object->has('UndefinedProperty')
        );
    }
}
