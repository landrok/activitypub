<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * Test accept accept headers
     */
    public static function getAcceptHeaderScenarios()
    {
        # input / expected
        return [
['application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
 true                                                                  ], # Allowed
['application/activity+json', true                                     ], # Allowed
['*/*', true                                                           ], # Allowed
['application/json', false                                             ], # Refused
[[
  'application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
  'application/json'
], true                                                                ], # Allowed (array input)
[[
  'application/pdf',
  'application/json'
], false                                                               ], # Refused (array input)

        ];
	}

    /**
     * @dataProvider getAcceptHeaderScenarios
     */
    public function testAcceptHeaderScenarios($input, $expected)
    {
        $this->assertEquals(
            $expected,
            Helper::validateAcceptHeader($input)
        );
    }
}
