<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server\Http\WebFinger;
use Exception;

class WebFingerTest extends ServerTestCase
{
    /**
     * Valid scenarios provider
     */
    public function getSuccessScenarios()
    {
        $sample = [
            'subject' => 'acct:bob@localhost:8000',
            'aliases' => [
                'http//localhost:8000/accounts/bob'
            ],
            'links' => [
                [
                    'rel' => 'self',
                    'type' => 'application/activity+json',
                    'href' => 'http://localhost:8000/accounts/bob',
                ]
            ]
        ];

        # handle / method / expected
        return [
            ['bob@localhost:8000', 'toArray', $sample], # toArray()
            ['bob@localhost:8000', 'getProfileId', $sample['links'][0]['href']], # getProfileId()
            ['bob@localhost:8000', 'getHandle', substr($sample['subject'], 5)], # getHandle()
            ['bob@localhost:8000', 'getSubject', $sample['subject']], # getSubject()
            ['bob@localhost:8000', 'getAliases', $sample['aliases']], # getAliases()
            ['bob@localhost:8000', 'getLinks', $sample['links']], # getLinks()
            ['http://localhost:8000/accounts/bob', 'toArray', $sample], # toArray() with an ActivityPhp profile id
        ];
    }

    /**
     * Exception scenarios provider
     */
    public function getFailingScenarios()
    {
        $sample = [
            'subject' => 'acct:bob@localhost:8000',
            'aliases' => [
                'http//localhost:8000/accounts/bob'
            ],
            'links' => [
                [
                    'rel' => 'self',
                    'type' => 'application/activity+json',
                    'href' => 'http://localhost:8000/accounts/bob',
                ]
            ]
        ];
        #
        return [
            ['bob@localhost:9000', 'toArray', $sample], # Bad host with an Handle
            ['bob', 'toArray', $sample], # Malformed handle
            ['http://localhost:9000/accounts/bob', 'toArray', $sample], # Bad port with an AS id
            ['http//localhost:8000/accounts/bob', 'toArray', $sample], # Bad scheme
            ['bob-subject-array@localhost:8000', 'toArray', $sample], # Bad response from server (Subject is an array)
            ['bob-malformed-aliases@localhost:8000', 'toArray', $sample], # Bad response from server (Aliases must be string[])
            ['bob-missing-links@localhost:8000', 'toArray', $sample], # Bad response from server (links key is not defined)
            ['bob-links-arrays@localhost:8000', 'toArray', $sample], # Bad response from server (links is an array of arrays)
            ['bob-links-missing-rel@localhost:8000', 'toArray', $sample], # Bad response from server (links key must contain a rel key)
            ['bob-404-profile@localhost:8000', 'toArray', $sample], # Bad response from server (404 Not found)

            ['http://localhost:8000/accounts/empty-profile', 'toArray', $sample], # Bad response from server (ActivityPhp profile is empty)
            ['http://localhost:8000/accounts/missing-property', 'toArray', $sample], # Bad response from server (Missing preferredUsername)
        ];
    }

    /**
     * Check that all response are valid
     *
     * @dataProvider getSuccessScenarios
     */
    public function testSuccessScenarios($handle, $method, $expected)
    {
        $server = $this->getServer();

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
     * @dataProvider getFailingScenarios
     * @param $handle
     * @param $method
     * @param $expected
     */
    public function testFailingScenarios($handle, $method, $expected)
    {
        $this->expectException(Exception::class);

        $server = $this->getServer();

        $server->actor($handle)->webfinger();
    }

    /**
     * Scenarios which throw an Exception
     */
    public function getFailingInstanceScenarios()
    {
        # data
        return [
            [
                [
                    'aliases' => ['http//localhost:8000/accounts/bob'],
                    'links' => [
                        [
                            'rel' => 'self',
                            'type' => 'application/activity+json',
                            'href' => 'http://localhost:8000/accounts/bob',
                        ]
                    ]
                ]
            ], # Missing key: subject
            [
                [
                    'subject' => 'acct:bob@localhost:8000',
                    'links' => [
                        [
                            'rel' => 'self',
                            'type' => 'application/activity+json',
                            'href' => 'http://localhost:8000/accounts/bob',
                        ]
                    ]
                ]
            ], # Missing key: aliases
            [
                [
                    'subject' => 'acct:bob@localhost:8000',
                    'aliases' => [
                        'http//localhost:8000/accounts/bob'
                    ],
                ]
            ], # Missing key: links
            [
                [
                    'subject' => ['acct:bob@localhost:8000'],
                    'aliases' => [
                        'http//localhost:8000/accounts/bob'
                    ],
                    'links' => [
                        [
                            'rel' => 'self',
                            'type' => 'application/activity+json',
                            'href' => 'http://localhost:8000/accounts/bob',
                        ]
                    ]
                ]
            ], # Malformed subject
            [
                [
                    'subject' => 'acct:bob@localhost:8000',
                    'aliases' => [
                        ['http//localhost:8000/accounts/bob']
                    ],
                    'links' => [
                        [
                            'rel' => 'self',
                            'type' => 'application/activity+json',
                            'href' => 'http://localhost:8000/accounts/bob',
                        ]
                    ]
                ]
            ], # Malformed aliases
            [
                [
                    'subject' => 'acct:bob@localhost:8000',
                    'aliases' => [
                        'http//localhost:8000/accounts/bob'
                    ],
                    'links' => [
                        'http://localhost:8000/accounts/bob',
                    ]
                ]
            ], # Malformed links: subelement is not an array
            [
                [
                    'subject' => 'acct:bob@localhost:8000',
                    'aliases' => [
                        'http//localhost:8000/accounts/bob'
                    ],
                    'links' => [
                        [
                            'type' => 'application/activity+json',
                            'href' => 'http://localhost:8000/accounts/bob',
                        ]
                    ]
                ]
            ], # Malformed links: subelement does not have a rel key
        ];
    }

    /**
     * Check that all tests are failing
     *
     * @dataProvider getFailingInstanceScenarios
     * @param $data
     * @throws Exception
     */
    public function testFailingInstanceScenarios($data)
    {
        $this->expectException(Exception::class);

        new WebFinger($data);
    }

    /**
     * Get profile id can return null if webfinger is used for an
     * implementation that does not support ActivityPhp
     */
    public function testEmptyProfileId()
    {
        $data = [
            'subject' => 'acct:bob@localhost:8000',
            'aliases' => [
                'http//localhost:8000/accounts/bob'
            ],
            'links' => [
                [
                    'rel' => 'self',
                    'type' => 'application/ld+json',
                    'href' => 'http://localhost:8000/accounts/bob',
                ]
            ]
        ];

        $webfinger = new WebFinger($data);

        // Assert 
        $this->assertEquals(
            null,
            $webfinger->getProfileId()
        );
    }
}
