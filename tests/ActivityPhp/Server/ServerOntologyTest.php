<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use PHPUnit\Framework\TestCase;
use ActivityPhp\Type\Ontology;
use Exception;

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

        $server = new Server([
            'ontologies' => $ontologies,
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

        $person = Type::create('Person', ['playlists' => 'bob']);

        $this->assertEquals(
            'bob',
            $person->getPlaylists()
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

        $server = new Server([
            'ontologies' => $ontologies,
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

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

        $server = new Server([
            'ontologies' => $ontologies,
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);
    }
}
