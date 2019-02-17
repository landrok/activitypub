<?php

namespace ActivityPhpTest;

use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\ValidatorInterface;

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
