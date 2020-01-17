<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type\TypeResolver;
use ActivityPhp\Type\Validator;
use ActivityPhp\TypeFactory;
use PHPUnit\Framework\TestCase;

class TypeResolverTest extends TestCase
{
    /**
     * Pass an object with a bad poolname
     */
    public function testIsScopeReturnFalse()
    {
        /** @var Validator $validator */
        $validator = $this->getMockBuilder(Validator::class)->disableOriginalConstructor()->getMock();

        $typeResolver = new TypeResolver();
        $typeFactory = new TypeFactory($typeResolver, $validator);
        $type = $typeFactory->create('Note');

        $this->assertEquals(
            false,
            $typeResolver->isScope($type, 'undefined scope')
        );
	}
}
