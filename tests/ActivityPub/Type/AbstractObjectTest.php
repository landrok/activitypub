<?php

namespace ActivityPubTest\Type;

use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Core\ObjectType;
use PHPUnit\Framework\TestCase;

class AbstractObjectTest extends TestCase
{
    /**
     * Test valid setters
     */
    public function testValidSetters()
    {
        $type = new ObjectType();

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
        $type = new ObjectType();

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
        $object = new ObjectType();
        $object->myCustomAttribute;
    }

    /**
     * Setting without argument should throw an exception
     * 
     * @expectedException \Exception
     */
    public function testSetWithNoArgument()
    {
        $object = new ObjectType();
        $object->setMyCustomAttribute();
    }

    /**
     * Call an undefined method
     * 
     * @expectedException \Exception
     */
    public function testCallUndefinedMethod()
    {
        $object = new ObjectType();
        $object->illegalCall();
    }

    /**
     * tests getProperties() method
     */
    public function testGetProperties()
    {	
        $object = new Link();

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
            $object->getProperties()
        );
    }

    /**
     * tests toArray() method
     */
    public function testToArray()
    {	
        $link = new Link();
        $link->setName('An example');
        $link->setHref('http://example.com');

        $expected = [
            'type' => 'Link',
            'id' => null,
            'name' => 'An example',
            'nameMap' =>null,
            'href' => 'http://example.com',
            'hreflang' =>null,
            'mediaType' =>null,
            'rel' =>null,
            'height' =>null,
            'preview' =>null,
            'width' =>null,
        ];

        $this->assertEquals(
            $expected, 
            $link->toArray()
        );
    }
}
