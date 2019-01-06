<?php

namespace ActivityPubTest;

use ActivityPub\Type\Core\ObjectType;

class MyCustomType extends ObjectType
{
    /**
     * A custom property
     * 
     * @var null|string
     */
    protected $customProperty;
}
