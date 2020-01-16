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
        $client = new Server\Http\GuzzleActivityPubClient();
        $server = new Server($httpFactory, $client, [
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
