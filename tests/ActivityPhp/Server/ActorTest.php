<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

/*
 * These scenarios are around server side actor instance
 */
class ActorTest extends TestCase
{
    /**
     * Check that an actor has no public key
     */
    public function testActorWithNoPublicKeyPem()
    {
        $httpFactory = new Psr17Factory();
        $client = new Server\Http\GuzzleActivityPubClient();
        $server = new Server($httpFactory, $client, [
            'instance' => [
                'host'  => 'localhost',
                'port'  => 8000,
                'debug' => true,
                'actorPath' => '/accounts/<handle>',
            ],
        ]);

        $actor = $server->actor('bob@localhost:8000');

        // Assert no public is set
        $this->assertEquals(
            false,
            $actor->getPublicKeyPem()
        );
    }
}
