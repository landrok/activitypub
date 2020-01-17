<?php

declare(strict_types=1);

namespace ActivityPhp\Dialects\Peertube\Type;

use ActivityPhp\Type\Extended\Object\Image as BaseImage;

class Image extends BaseImage
{

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;
}