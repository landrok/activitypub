<?php

namespace ActivityPubTest\Type;

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


}
