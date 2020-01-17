<?php


namespace ActivityPhp\Dialects\Peertube\Type;

use ActivityPhp\Type\Extended\Actor\Group as BaseGroup;

final class Group extends BaseGroup
{

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $playlists;

    /**
     * @var string
     */
    protected $support;
}
