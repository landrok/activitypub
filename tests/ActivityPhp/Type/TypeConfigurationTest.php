<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type;
use ActivityPhp\Type\TypeConfiguration as Config;
use PHPUnit\Framework\TestCase;

class TypeConfigurationTest extends TestCase
{
    /**
     * Giving a value for an undefined property - 'ignore' mode
     * This should return null as property is ignored
     */
    public function testIgnoreModeDoesNotThrowException()
    {
        $type = Type::create('Note');

        Config::set('undefined_properties', 'ignore');

        $this->assertEquals(
            null,
            $type->undefined_property
        );

        $type->undefined_property = 'OK';

        $this->assertEquals(
            null,
            $type->undefined_property
        );
    }

    /**
     * Giving a value for an undefined property - 'include' mode
     * This should returns the value when defined.
     */
    public function testIncludeModeDoesNotThrowException()
    {
        $type = Type::create('Note');

        Config::set('undefined_properties', 'include');

        $this->assertEquals(
            null,
            $type->undefined_property
        );

        $type->undefined_property = 'OK';

        $this->assertEquals(
            'OK',
            $type->undefined_property
        );
    }

    /**
     * Include mode create new types on the fly
     */
    public function testIncludeModeDoesCreateNewTypes()
    {
        Config::set('undefined_properties', 'include');

        $type = Type::create('CustomIncludeType');

        $this->assertEquals(
            'CustomIncludeType',
            $type->type
        );

        Config::set('undefined_properties', 'strict');
    }
}
