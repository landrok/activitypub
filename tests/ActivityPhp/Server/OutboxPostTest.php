<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OutboxPostTest extends TestCase
{
    /**
     * Valid scenarios provider
     */
    public static function getOutboxPostActivities()
    {
        # Activities or objects
        return [
            ['{
              "@context": ["https://www.w3.org/ns/activitystreams",
                           {"@language": "en"}],
              "type": "Like",
              "actor": "https://dustycloud.org/chris/",
              "name": "Chris liked \'Minimal ActivityPhp update client\'",
              "object": "https://rhiaro.co.uk/2016/05/minimal-activitypub",
              "to": ["https://rhiaro.co.uk/#amy",
                     "https://dustycloud.org/followers",
                     "https://rhiaro.co.uk/followers/"],
              "cc": "https://e14n.com/evan"
            }'                                                                     ], # JSON formatted Like activity
            ['{
              "@context": "https://www.w3.org/ns/activitystreams",
              "type": "Note",
              "content": "This is a note",
              "published": "2015-02-10T15:04:55Z",
              "to": ["https://example.org/~john/"],
              "cc": ["https://example.com/~erik/followers",
                     "https://www.w3.org/ns/activitystreams#Public"]
            }'                                                                     ], # JSON formatted Note that should be wrapped into activity



            ['{
              "@context": "https://www.w3.org/ns/activitystreams",
              "type": "Note",
              "content": "This is a note",
              "published": "2015-02-10T15:04:55Z",
              "to": ["https://example.org/~john/"],
              "cc": ["https://example.com/~erik/followers",
                     "https://www.w3.org/ns/activitystreams#Public"]
            }',
            'application/ld+json; profile="https://www.w3.org/ns/activitystreams"' ], # Accept headers should be accepted

            // ---------------------------------------------------------------------
            // Error scenarios
            // ---------------------------------------------------------------------
            ['bad JSON', 'application/activity+json', 400                          ], # Bad JSON payload should return 400 Bad request
            ['bad JSON', 'text/html,application/xhtml+xml,application/xml', 400    ], # Accept header MUST be valid
        ];
	}

    /**
     * Check that all response are valid
     */
    #[DataProvider('getOutboxPostActivities')]
    public function testOutboxPostActivities($payload, $accept = 'application/activity+json', $code = 201)
    {
        $server = new Server([
            'instance'  => [
                'debug' => true,
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'http'    => [
               'timeout' => 15
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

        $request = Request::create(
            'http://localhost:8000',
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            $_SERVER,
            $payload
        );
        $request->headers->set('accept', $accept);

        $response = $server->outbox('bob@localhost:8000')->post($request);

        // Assert response type
        $this->assertInstanceOf(Response::class, $response);

        // Assert HTTP status code
        $this->assertEquals(
            $code,
            $response->getStatusCode()
        );
    }
}
