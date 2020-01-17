<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server\Configuration;
use Exception;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * Scenarios which throw an Exception
     */
    public function getFailingInstanceScenarios()
    {
        # data
        return [
[[['http']]                                                            ], # Key is an array
[['https' => 'bob']                                                    ], # property does not exist
[['http' => 'bob']                                                     ], # property must have an array as value

        ];
	}

    /**
     * Check that all tests are failing
     *
     * @dataProvider      getFailingInstanceScenarios
     */
    public function testFailingInstanceScenarios($data)
    {
        $this->expectException(Exception::class);

        $config = new Configuration($data);
    }

    /**
     * Check a call of getConfig() with a non existing parameter
     * throws an Exception
     */
    public function testFailingOnNonExistingParameter()
    {
        $this->expectException(Exception::class);

        $config = new Configuration();
        
        $config->getConfig('https');
    }
}
