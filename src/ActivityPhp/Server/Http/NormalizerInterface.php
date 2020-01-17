<?php

namespace ActivityPhp\Server\Http;

use ActivityPhp\Type\AbstractObject;

interface NormalizerInterface
{
    public function normalize(AbstractObject $object): array;
}