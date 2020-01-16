<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

/*
 * These scenarios are around server caching
 */
class CacheTest extends TestCase
{
    /**
     * Check that an actor is cached
     */
    public function testActorCacheSet()
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
                'enabled' => true,
            ]
        ], $httpFactory);

        $actor = $server->actor('bob@localhost:8000');

        // Assert no public key is set
        $this->assertFalse($actor->getPublicKeyPem());
    }
}
