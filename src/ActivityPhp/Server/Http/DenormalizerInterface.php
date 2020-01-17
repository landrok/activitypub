<?php

namespace ActivityPhp\Server\Http;

use ActivityPhp\Type\AbstractObject;

interface DenormalizerInterface
{
    public function denormalize(array $data);
}
