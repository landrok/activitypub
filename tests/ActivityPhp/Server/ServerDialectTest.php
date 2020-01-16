<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

/*
 * These scenarios are around dialects loading
 */
class ServerDialectTest extends TestCase
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

        $httpFactory = new Psr17Factory();

        $server = new Server([
            'dialects' => [
                'mydialect' => $dialect
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

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
