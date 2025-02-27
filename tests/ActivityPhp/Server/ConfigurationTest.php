<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use PHPUnit\Framework\Attributes\DataProvider;
use ActivityPhp\Server\Configuration;
use Exception;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * Scenarios which throw an Exception
     */
    public static function getFailingInstanceScenarios()
    {
        # data
        return [
            [[['http']]          ], # Key is an array
            [['https' => 'bob']  ], # property does not exist
            [['http' => 'bob']   ], # property must have an array as value
        ];
	}

    #[DataProvider('getFailingInstanceScenarios')]
    public function testFailingInstanceScenarios($data)
    {
        $this->expectException(Exception::class);

        $config = new Configuration();
        $config->dispatchParameters($data);
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
