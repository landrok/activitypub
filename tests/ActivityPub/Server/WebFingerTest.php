<?php

namespace ActivityPubTest\Server;

use ActivityPub\Server;
use PHPUnit\Framework\TestCase;

class WebFingerTest extends TestCase
{
    /**
     * Valid scenarios provider
     */
    public function getSuccessScenarios()
    {
        $sample = [
            'subject' => 'acct:bob@ap.localhost:8000',
            'aliases' => [
                'http//ap.localhost:8000/accounts/bob'
            ],
            'links' => [
                [
                    'rel' => 'self',
                    'type' => 'application/activity+json',
                    'href' => 'http://ap.localhost:8000/accounts/bob',
                ]
            ]
        ];
    
        # handle / method / expected
        return [
['bob@ap.localhost:8000', 'toArray', $sample                           ], # toArray()
['bob@ap.localhost:8000', 'getProfileId', $sample['links'][0]['href']  ], # getProfileId()
['bob@ap.localhost:8000', 'getHandle', substr($sample['subject'], 5)   ], # getHandle()
['bob@ap.localhost:8000', 'getSubject', $sample['subject']             ], # getSubject()
['bob@ap.localhost:8000', 'getAliases', $sample['aliases']             ], # getAliases()
['bob@ap.localhost:8000', 'getLinks', $sample['links']                 ], # getLinks()
['http://ap.localhost:8000/accounts/bob', 'toArray', $sample           ], # toArray() with an ActivityPub profile id
        ];
	}

    /**
     * Exception scenarios provider
     */
    public function getFailingScenarios()
    {
        $sample = [
            'subject' => 'acct:bob@ap.localhost:8000',
            'aliases' => [
                'http//ap.localhost:8000/accounts/bob'
            ],
            'links' => [
                [
                    'rel' => 'self',
                    'type' => 'application/activity+json',
                    'href' => 'http://ap.localhost:8000/accounts/bob',
                ]
            ]
        ];
        #
        return [
['bob@ap.localhost:9000', 'toArray', $sample                           ], # Bad host with an Handle
['bob', 'toArray', $sample                                             ], # Malformed handle
['http://ap.localhost:9000/accounts/bob', 'toArray', $sample           ], # Bad port with an AS id
['http//ap.localhost:8000/accounts/bob', 'toArray', $sample            ], # Bad scheme
['bob-subject-array@ap.localhost:8000', 'toArray', $sample             ], # Bad response from server (Subject is an array)
['bob-malformed-aliases@ap.localhost:8000', 'toArray', $sample         ], # Bad response from server (Aliases must be string[])
['bob-missing-links@ap.localhost:8000', 'toArray', $sample             ], # Bad response from server (links key is not defined)
['bob-links-arrays@ap.localhost:8000', 'toArray', $sample              ], # Bad response from server (links is an array of arrays)
['bob-links-missing-rel@ap.localhost:8000', 'toArray', $sample         ], # Bad response from server (links key must contain a rel key)
['bob-404-profile@ap.localhost:8000', 'toArray', $sample               ], # Bad response from server (404 Not found)
        ];
	}

    /**
     * Check that all response are valid
     *
     * @dataProvider getSuccessScenarios
     */
    public function testSuccessScenarios($handle, $method, $expected)
    {
        $server = new Server([
            'instance'  => [
                'debug' => true,
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ]
        ]);

        $webfinger = $server->actor($handle)->webfinger();

        // Assert 
        $this->assertEquals(
            $expected,
            $webfinger->$method()
        );
    }

    /**
     * Check that all tests are failing
     *
     * @dataProvider      getFailingScenarios
     * @expectedException \Exception
     */
    public function testFailingScenarios($handle, $method, $expected)
    {
        $server = new Server([
            'instance'  => [
                'debug' => true,
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ]
        ]);

        $webfinger = $server->actor($handle)->webfinger();
    }
}
