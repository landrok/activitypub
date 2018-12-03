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
}
