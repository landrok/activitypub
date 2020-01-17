<?php

declare(strict_types=1);

namespace ActivityPhp\Dialects\Peertube;

use ActivityPhp\Dialects\Peertube\Type\Group as PeertubeGroup;
use ActivityPhp\Dialects\Peertube\Type\Hashtag as PeertubeHashtag;
use ActivityPhp\Dialects\Peertube\Type\Image as PeertubeImage;
use ActivityPhp\Dialects\Peertube\Type\Link as PeertubeLink;
use ActivityPhp\Dialects\Peertube\Type\Person as PeertubePerson;
use ActivityPhp\Dialects\Peertube\Type\Video as PeertubeVideo;
use ActivityPhp\Server;

final class PeertubeDialect implements \DialectInterface
{

    /**
     * @var Server
     */
    private $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function load(): void
    {
        $typeFactory = $this->server->getTypeFactory();

        $typeFactory->add('Person', PeertubePerson::class);
        $typeFactory->add('Group', PeertubeGroup::class);
        $typeFactory->add('Hashtag', PeertubeHashtag::class);
        $typeFactory->add('Image', PeertubeImage::class);
        $typeFactory->add('Link', PeertubeLink::class);
        $typeFactory->add('Video', PeertubeVideo::class);
    }

    public function unload(): void
    {
        // TODO: Implement unload() method.
    }
}