<?php

namespace ActivityPubTest;

use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\ValidatorInterface;

class MyCustomValidator implements ValidatorInterface
{
    /**
     * Validate a custom attribute value
     * 
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     */
    public function validate($value, $container)
    {
        return $value != 'Bad value'
            ? true
            : false;
    }
}
