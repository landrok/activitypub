<?php

declare(strict_types=1);

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;

abstract class ServerTestCase extends TestCase
{

    /**
     * @var RequestFactoryInterface|ResponseFactoryInterface
     */
    protected $httpFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpFactory = new Psr17Factory();
    }

    public function getServer(array $config = []): Server
    {
        $activityPubClient = new Server\Http\GuzzleActivityPubClient();
        $webfingerClient = new Server\Http\WebFingerClient($activityPubClient, false);

        return new Server($this->httpFactory, $activityPubClient, $webfingerClient, $config);
    }
}