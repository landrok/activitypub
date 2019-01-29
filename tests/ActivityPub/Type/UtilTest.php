<?php

namespace ActivityPubTest\Type;

use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    /**
     * Pass a non object as container
     */
    public function testIsTypeReturnFalse()
    {
        $this->assertEquals(
            false, 
            Util::isType('hello', 'type')
        );
	}

    /**
     * Pass an object which is not a subclass
     * without strict mode.
     */
    public function testIsNotASubclass()
    {
        $obj = new \StdClass;

        $this->assertEquals(
            false, 
            Util::subclassOf($obj, 'Class')
        );
	}

    /**
     * Pass an object which is not a subclass
     * with strict mode.
     * 
     * @expectedException \Exception
     */
    public function testIsStrictlyNotASubclass()
    {
        $obj = new \StdClass;

        $this->assertEquals(
            false, 
            Util::subclassOf($obj, 'Class', true)
        );
	}

    /**
     * Pass an malformed XML ISO 8601 duration
     * without strict mode.
     */
    public function testIsNotAValidDuration()
    {
        $this->assertEquals(
            false, 
            Util::isDuration('MALFORMED')
        );
	}

    /**
     * Test between() method.
     */
    public function testBetween()
    {
        // tests true
        $this->assertEquals(true, Util::between(0, 0, 20));
        $this->assertEquals(true, Util::between(10.5, 10, null));
        $this->assertEquals(true, Util::between(10, null, 10));
        $this->assertEquals(true, Util::between(15, 15, null));
        $this->assertEquals(true, Util::between(-9.6, -10, 10));

        // tests false
        $this->assertEquals(false, Util::between(0, 10, 20));
        $this->assertEquals(false, Util::between(0, 10, null));
        $this->assertEquals(false, Util::between(15, null, 10));
        $this->assertEquals(false, Util::between(15, null, null));
        $this->assertEquals(false, Util::between("Hello", -10, 10));
	}

    /**
     * Pass an illegal type for validateBcp47 string
     */
    public function testIsNotAValidBcp47Type()
    {
        $this->assertEquals(
            false, 
            Util::validateBcp47([])
        );
	}

    /**
     * Pass an illegal type for validateCollection
     */
    public function testValidateCollectionNotAnObject()
    {
        $this->assertEquals(
            false, 
            Util::validateCollection('MyStringCollection')
        );
	}

    /**
     * Pass an illegal type for validateCollectionPage
     * 
     * @expectedException \Exception
     */
    public function testValidateCollectionPageInvalidObject()
    {
        Util::validateCollectionPage(new ObjectType());
	}

    /**
     * Pass a malformed JSON string
     * 
     * @expectedException \Exception
     */
    public function testDecodeJsonFailing()
    {
        Util::decodeJson('hello');
	}

    /**
     * Pass JSON string and get an array
     */
    public function testDecodeJson()
    {
        $this->assertEquals(
            ['name' => 'hello'], 
            Util::decodeJson('{"name":"hello"}')
        );
	}

    /**
     * An object must have a property.
     * In non strict mode, it must return false
     */
    public function testHasPropertiesFailingNonStrictMode()
    {
        $item = new \StdClass;

        $this->assertEquals(
            false, 
            Util::hasProperties($item, ['type'])
        );
	}
}
