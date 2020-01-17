<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

/*
 * These scenarios are around dialects loading
 */
class ServerDialectTest extends ServerTestCase
{
    /**
     * Check that a dialect can be define from server configuration
     */
    public function testDialectsServerLoading()
    {
        $dialect = [
            'Person' => ['featured'],
            'PropertyValue' => ['value']
        ];

        $server = $this->getServer([
            'dialects' => [
                'mydialect' => $dialect
            ],
        ]);

        $prop = Type::create('PropertyValue', ['value' => 1]);
        $person = Type::create('Person', ['featured' => 2]);

        $this->assertEquals(
            1,
            $prop->getValue()
        );

        $this->assertEquals(
            2,
            $person->getFeatured()
        );
    }
}
