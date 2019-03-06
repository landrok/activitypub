<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phpseclib\Crypt\RSA;

/*
 * These scenarios are around server side actor instance
 */
class ActorTest extends TestCase
{
    /**
     * Check that a given request is correctly signed
     */
    public function testActorWithNoPublicKeyPem()
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
                'enabled' => false,
            ]
        ]);

        $actor = $server->actor('bob@localhost:8000');

        // Assert no public is set
        $this->assertEquals(
            false,
            $actor->getPublicKeyPem()
        );
    }
}
