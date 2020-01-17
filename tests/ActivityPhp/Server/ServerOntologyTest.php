<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Type;
use ActivityPhp\Type\Ontology;
use Exception;
use ActivityPhpTest\MyCustomOntology;

/*
 * These scenarios are around ontologies loading
 */
class ServerOntologyTest extends ServerTestCase
{
    /**
     * Check that an ontology can be define from server configuration
     */
    public function testOntologyServerLoading()
    {
        $ontologies = [
            'peertube'
        ];

        $server = $this->getServer([
            'ontologies' => $ontologies,
        ]);

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

        $server = $this->getServer([
            'ontologies' => $ontologies,
        ]);

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

        $server = $this->getServer([
            'ontologies' => $ontologies,
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

        $server = $this->getServer([
            'ontologies' => $ontologies,
        ]);
    }
}
