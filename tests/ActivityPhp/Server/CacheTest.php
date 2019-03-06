<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
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
        ]);

        $actor = $server->actor('bob@localhost:8000');
        $actor = $server->actor('bob@localhost:8000');

        // Assert no public key is set
        $this->assertEquals(
            false,
            $actor->getPublicKeyPem()
        );
    }
}
