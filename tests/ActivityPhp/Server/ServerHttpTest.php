<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ParagonIE\Certainty\RemoteFetch;
use PHPUnit\Framework\TestCase;

class ServerHttpTest extends TestCase
{
    public function testDefaultUserAgent()
    {
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ],
            'http' => [],
            'instance' => [
                'host' => 'custom.federated',
            ],
        ]);

        //
        // PHPUnit >= 9
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression(
                '/ActivityPhp\/\d.\d.\d \(\+https:\/\/custom.federated\)/',
                Server::server()->config('http.agent')
            );
        // PHPUnit < 9
        } else {
            $this->assertRegExp(
                '/ActivityPhp\/\d.\d.\d \(\+https:\/\/custom.federated\)/',
                Server::server()->config('http.agent')
            );
        }
    }

    public function testUserAgentCustomization()
    {
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ],
            'http' => [
                'agent' => "MyUserAgent"
            ],
            'instance' => [
                'host' => 'custom.federated',
            ],
        ]);

        $this->assertEquals(
            Server::server()->config('http.agent'),
            "MyUserAgent"
        );
    }

    public function testCaCert()
    {
        mkdir(__DIR__ . '/tmp');
        $latestCaCert = (new RemoteFetch(__DIR__ . '/tmp'))
            ->getLatestBundle(false, false)
            ->getFilePath();
        $server = new Server([
            'http' => [
                'cacert' => $latestCaCert
            ]
        ]);
        $this->assertSame(
            $latestCaCert,
            $server->config('http.cacert')
        );
    }
}
