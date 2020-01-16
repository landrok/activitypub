<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Server\Actor\AbstractBox;
use ActivityPhp\Type\Core\OrderedCollection;
use ActivityPhp\Type\Core\OrderedCollectionPage;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

class FetchOutboxTest extends TestCase
{
    /**
     * Check that all response are valid
     */
    public function testSuccessFetch()
    {
        $httpFactory = new Psr17Factory();
        $client = new Server\Http\GuzzleActivityPubClient();
        $server = new Server($httpFactory, $client, [
            'instance'  => [
                'debug' => true,
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

        $handle = 'bob@localhost:8000';

        $outbox = $server->outbox($handle);

        // A box instance 
        $this->assertInstanceOf(
            AbstractBox::class,
            $outbox
        );

        // Outbox is an OrderedCollection
        $this->assertInstanceOf(
            OrderedCollection::class,
            $outbox->get()
        );

        // First is valid URL
        // Fetching return an OrderedCollectionPage
        $this->assertInstanceOf(
            OrderedCollectionPage::class,
            $outbox->getPage(
                $outbox->get()->first
            )
        );

        // First activity is a Note
        $this->assertEquals(
            'Create',
            $outbox->getPage(
                $outbox->get()->first
            )->orderedItems[0]->type
        );
    }
}
