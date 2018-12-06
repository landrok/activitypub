<?php

namespace ActivityPubTest\Type;

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
}
