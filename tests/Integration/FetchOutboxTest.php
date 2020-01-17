<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server\Actor\AbstractBox;
use ActivityPhp\Type\Core\OrderedCollection;
use ActivityPhp\Type\Core\OrderedCollectionPage;

class FetchOutboxTest extends ServerTestCase
{
    /**
     * Check that all response are valid
     */
    public function testSuccessFetch()
    {
        $server = $this->getServer([
            'instance'  => [
                'debug' => true,
            ],
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

        $page = $outbox->getPage($outbox->get()->first);

        // First activity is a Note
        $this->assertEquals(
            'Create',
            $page->orderedItems[0]->type
        );
    }
}
