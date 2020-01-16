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

        $server = new Server([
            'instance' => [
                'host'  => 'localhost',
                'port'  => 8000,
                'debug' => true,
                'actorPath' => '/accounts/<handle>',
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $actor = $server->actor('bob@localhost:8000');

        // Assert no public is set
        $this->assertEquals(
            false,
            $actor->getPublicKeyPem()
        );
    }
}
