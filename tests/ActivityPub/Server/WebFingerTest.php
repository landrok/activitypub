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
}
