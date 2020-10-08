<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Server\Cache\CacheHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

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

    /**
     * Check that cache->set is working with an array driver
     * given with a string parameter.
     */
    public function testCacheArrayDriverAsString()
    {
        $server = new Server([
            'cache' => [
                'pool'  => '\Symfony\Component\Cache\Adapter\ArrayAdapter'
            ]
        ]);

        CacheHelper::set('123456789', 'testValue');

        $this->assertEquals(
            'testValue',
            CacheHelper::get('123456789')
        );
    }

    /**
     * Check that cache->set is working with an array driver
     * given with an instanciated pool
     */
    public function testCacheArrayDriverAsInstanciatedPool()
    {
        $server = new Server([
            'cache' => [
                'pool'  => new ArrayAdapter
            ]
        ]);

        CacheHelper::set('12345678', 'testValue');

        $this->assertEquals(
            'testValue',
            CacheHelper::get('12345678')
        );
    }

    /**
     * Check that cache configuration is throwing an exception if
     * given pool driver is not Psr\Cache compliant
     */
    public function testCacheMustBePsrCacheCompliant()
    {
        $this->expectException(\Exception::class);

        $server = new Server([
            'cache' => [
                'pool'  => new \stdClass
            ]
        ]);
    }

    /**
     * Check that cache configuration is throwing an exception if
     * given configuration does not satisfy requirements
     */
    public function testCacheInstanciationFailedForRequirements()
    {
        $this->expectException(\Exception::class);

        $server = new Server([
            'cache' => [
                'pool'  => 123
            ]
        ]);
    }
}
