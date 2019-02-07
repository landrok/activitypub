<?php

namespace ActivityPubTest;

use ActivityPub\Type\Extended\Actor\Person;

class MyCustomType extends Person
{
    /**
     * A custom property
     * 
     * @var null|string
     */
    protected $customProperty;

    /**
     * A custom property without any validator
     * 
     * @var mixed
     */
    protected $customFreeProperty;
}
