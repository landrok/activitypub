<?php

declare(strict_types=1);

namespace ActivityPhp\Dialects\Peertube\Type;

use ActivityPhp\Type\Extended\Actor\Person as BasePerson;

final class Person extends BasePerson
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
}