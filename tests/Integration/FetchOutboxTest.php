<?php

namespace ActivityPubTest\Server;

use ActivityPub\Server;
use ActivityPub\Server\Actor\AbstractBox;
use ActivityPub\Type\Core\OrderedCollection;
use ActivityPub\Type\Core\OrderedCollectionPage;
use PHPUnit\Framework\TestCase;

class FetchOutboxTest extends TestCase
{
    /**
     * Check that all response are valid
     */
    public function testSuccessFetch()
    {
        $server = new Server([
            'instance'  => [
                'debug' => true,
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
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
/*
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
*/

}
