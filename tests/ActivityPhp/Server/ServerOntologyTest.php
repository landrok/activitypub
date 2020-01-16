<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use ActivityPhp\Type\Ontology;
use Exception;
use ActivityPhpTest\MyCustomOntology;

/*
 * These scenarios are around ontologies loading
 */
class ServerOntologyTest extends TestCase
{
    /**
     * Check that an ontology can be define from server configuration
     */
    public function testOntologyServerLoading()
    {
        $ontologies = [
            'peertube'
        ];

        $httpFactory = new Psr17Factory();

        $server = new Server([
            'ontologies' => $ontologies,
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $person = Type::create('Person', ['playlists' => 'bob']);

        $this->assertEquals(
            'bob',
            $person->getPlaylists()
        );
        
        Ontology::clear();
    }

    /**
     * Add a new ontology
     */
    public function testAddCustomOntology()
    {
        $ontologies = [
            'custom-ontology' => MyCustomOntology::class,
        ];

        $httpFactory = new Psr17Factory();

        $server = new Server([
            'ontologies' => $ontologies,
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $person = Type::create('Person', ['myOntologyField' => 'bob']);

        $this->assertEquals(
            'bob',
            $person->myOntologyField
        );

        Ontology::clear();
	}

    /**
     * Check that an ontology can be define from server configuration
     */
    public function testLoadAllOntologiesServerLoading()
    {
        $ontologies = [
            '*'
        ];

        $httpFactory = new Psr17Factory();

        $server = new Server([
            'ontologies' => $ontologies,
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $person = Type::create('Person', ['playlists' => 'bob']);

        $this->assertEquals(
            'bob',
            $person->getPlaylists()
        );

        Ontology::clear();
    }

    /**
     * Try to load an undefined ontology
     */
    public function testLoadUndefinedOntologyLoading()
    {
        $this->expectException(Exception::class);

        $ontologies = [
            'undefined'
        ];


        $httpFactory = new Psr17Factory();

        $server = new Server([
            'ontologies' => $ontologies,
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);
    }
}
