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
     */
    public function validate($value, $container): bool
    {
        return $value !== 'Bad value'
            ? true
            : false;
    }
}
