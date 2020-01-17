<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type;

use Exception;

/**
 * \ActivityPhp\Type\Validator is an abstract class for
 * attribute validation.
 */
class Validator
{
    /**
     * Contains all custom validators
     * 
     * @var array
     * 
     * [ 'attributeName' => CustomValidatorClassName::class ]
     */
    protected $validators = [];

    /**
     * Validate an attribute value for given attribute name and 
     * container object.
     * 
     * @param string $name
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     * @throws \Exception if $container is not an object
     */
    public function validate($name, $value, $container)
    {
        if (!is_object($container)) {
            throw new Exception(
                'Given container is not an object'
            );
        }

        // Perform validation
        if (isset($this->validators[$name])) {
            return $this->validators[$name]->validate($value, $container);
        }

        // There is no validator for this attribute
        return true;
    }

    /**
     * Add a new validator in the pool.
     * It checks that it implements Validator\Interface
     *
     * @param string $name An attribute name to validate.
     * @param ValidatorInterface $validator
     */
    public function add($name, ValidatorInterface $validator)
    {
        $this->validators[$name] = $validator;
    }
}
