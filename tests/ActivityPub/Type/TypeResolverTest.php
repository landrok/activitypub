<?php

namespace ActivityPubTest\Type;

use ActivityPub\Type;
use ActivityPub\Type\TypeResolver;
use PHPUnit\Framework\TestCase;

class TypeResolverTest extends TestCase
{
    /**
     * Pass an object with a bad poolname
     */
    public function testIsScopeReturnFalse()
    {
        $type = Type::create('Note');

        $this->assertEquals(
            false, 
            TypeResolver::isScope($type, 'undefined scope')
        );
	}
}
