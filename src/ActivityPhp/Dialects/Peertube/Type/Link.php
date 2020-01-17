<?php

declare(strict_types=1);

namespace ActivityPhp\Dialects\Peertube\Type;

use ActivityPhp\Type\Core\Link as BaseLink;

final class Link extends BaseLink
{

    /**
     * @var int
     */
    protected $fps;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var int
     */
    protected $size;
}