<?php

namespace ActivityPubTest\Type;

use ActivityPub\Type\Core\ObjectType;

class MyCustomType extends ObjectType
{
    /**
     * @var null|string
     * 
     * A custom property
     */
    protected $customProperty;
}
