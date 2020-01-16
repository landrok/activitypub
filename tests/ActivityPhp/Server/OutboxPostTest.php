<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class OutboxPostTest extends TestCase
{
    /**
     * Valid scenarios provider
     */
    public function getOutboxPostActivities()
    {
        # Activities or objects
        return [
            [
                '{
                  "@context": ["https://www.w3.org/ns/activitystreams",
                               {"@language": "en"}],
                  "type": "Like",
                  "id": "http://localhost:8000/~chris/like/ca1cb81a-f735-4674-b540-6756f9df5d8b",
                  "actor": "https://dustycloud.org/chris/",
                  "name": "Chris liked \'Minimal ActivityPhp update client\'",
                  "object": "https://rhiaro.co.uk/2016/05/minimal-activitypub",
                  "to": ["https://rhiaro.co.uk/#amy",
                         "https://dustycloud.org/followers",
                         "https://rhiaro.co.uk/followers/"],
                  "cc": "https://e14n.com/evan"
                }'
            ], # JSON formatted Like activity
            [
                '{
                  "@context": "https://www.w3.org/ns/activitystreams",
                  "type": "Note",
                  "id": "http://localhost:8000/~chris/like/ca1cb81a-f735-4674-b540-6756f9df5d8b",
                  "content": "This is a note",
                  "published": "2015-02-10T15:04:55Z",
                  "to": ["https://example.org/~john/"],
                  "cc": ["https://example.com/~erik/followers",
                         "https://www.w3.org/ns/activitystreams#Public"]
                }'
            ], # JSON formatted Note that should be wrapped into activity
            [
                '{
                  "@context": "https://www.w3.org/ns/activitystreams",
                  "type": "Note",
                  "id": "http://localhost:8000/~chris/like/ca1cb81a-f735-4674-b540-6756f9df5d8b",
                  "content": "This is a note",
                  "published": "2015-02-10T15:04:55Z",
                  "to": ["https://example.org/~john/"],
                  "cc": ["https://example.com/~erik/followers",
                         "https://www.w3.org/ns/activitystreams#Public"]
                }',
                'application/ld+json; profile="https://www.w3.org/ns/activitystreams"'
            ], # Accept headers should be accepted
            // ---------------------------------------------------------------------
            // Error scenarios
            // ---------------------------------------------------------------------
            ['bad JSON', 'application/activity+json', 400], # Bad JSON payload should return 400 Bad request
            ['bad JSON', 'text/html,application/xhtml+xml,application/xml', 400], # Accept header MUST be valid
        ];
    }

    /**
     * Check that all response are valid
     *
     * @dataProvider getOutboxPostActivities
     * @param $payload
     * @param string $accept
     * @param int $code
     * @throws \Exception
     */
    public function testOutboxPostActivities($payload, $accept = 'application/activity+json', $code = 201)
    {
        $httpFactory = new Psr17Factory();
        $client = new Server\Http\GuzzleActivityPubClient();
        $server = new Server($httpFactory, $client, [
            'instance' => [
                'debug' => true,
            ],
            'logger' => [
                'driver' => '\Psr\Log\NullLogger'
            ],
            'http' => [
                'timeout' => 15
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

        $request = $httpFactory->createServerRequest('POST', 'http://localhost:8000', $_SERVER)
            ->withHeader('accept', $accept);

        $request->getBody()->write($payload);

        $response = $server->outbox('bob@localhost:8000')->post($request);

        // Assert response type
        $this->assertInstanceOf(ResponseInterface::class, $response);

        // Assert HTTP status code
        $this->assertEquals($code, $response->getStatusCode());
    }
}
