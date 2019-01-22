<?php

namespace ActivityPubTest\Server;

use ActivityPub\Server;
use ActivityPub\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class OutboxPostTest extends TestCase
{
    /**
     * Valid scenarios provider
     */
    public function getOutboxPostActivities()
    {
        # Activities or objects
        return [
['{
  "@context": ["https://www.w3.org/ns/activitystreams",
               {"@language": "en"}],
  "type": "Like",
  "actor": "https://dustycloud.org/chris/",
  "name": "Chris liked \'Minimal ActivityPub update client\'",
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

[[
  "@context" => "https://www.w3.org/ns/activitystreams",
  "type" => "Note",
  "content" => "This is a note",
  "published" => "2015-02-10T15:04:55Z",
  "to" => ["https://example.org/~john/"],
  "cc" => ["https://example.com/~erik/followers",
         "https://www.w3.org/ns/activitystreams#Public"]
]                                                                      ], # Array formatted Note that should be wrapped into activity

[Type::create([
  "@context" => "https://www.w3.org/ns/activitystreams",
  "type" => "Note",
  "content" => "This is a note",
  "published" => "2015-02-10T15:04:55Z",
  "to" => ["https://example.org/~john/"],
  "cc" => ["https://example.com/~erik/followers",
         "https://www.w3.org/ns/activitystreams#Public"]
])                                                                     ], # Type formatted Note that should be wrapped into activity



        ];
	}

    /**
     * Check that all response are valid
     *
     * @dataProvider getOutboxPostActivities
     */
    public function testOutboxPostActivities($payload)
    {
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ]
        ]);

        $response = $server->outbox('bob')->post($payload);

        // Assert response type
        $this->assertInstanceOf(Response::class, $response);

        // Assert HTTP status code
        $this->assertEquals(
            201,
            $response->getStatusCode()
        );
    }
}
