<?php

namespace ActivityPubTest\Type;

use ActivityPub\Type;
use ActivityPubTest\MyCustomType;
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
     * 
     * @expectedException \Exception
     */
    public function testGetEmptyProperty()
    {
        $object = Type::create('ObjectType');
        $object->myCustomAttribute;
    }

    /**
     * Setting without argument should throw an exception
     * 
     * @expectedException \Exception
     */
    public function testSetWithNoArgument()
    {
        $object = Type::create('ObjectType');
        $object->setMyCustomAttribute();
    }

    /**
     * Call an undefined method
     * 
     * @expectedException \Exception
     */
    public function testCallUndefinedMethod()
    {
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
        $expected = [
            'type' => 'MyCustomType',
            'customFreeProperty' => 'Free value',
        ];

        $this->assertEquals(
            $expected, 
            Type::create(MyCustomType::class, $expected)->toArray()
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
}
