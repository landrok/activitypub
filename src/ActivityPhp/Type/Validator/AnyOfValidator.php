<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Validator;

use ActivityPhp\Type\Extended\Activity\Question;
use ActivityPhp\Type\Util;
use ActivityPhp\Type\ValidatorTools;

/**
 * \ActivityPhp\Type\Validator\AnyOfValidator is a dedicated
 * validator for anyOf attribute.
 */
class AnyOfValidator extends ValidatorTools
{
    /**
     * Validate an ANYOF attribute value
     * 
     * @param mixed  $value
     * @param mixed  $container An object
     * @return bool
     * @todo Choices can contain Indirect references.
     * 		This validation should validate this kind of usage.
     */
    public function validate($value, $container)
    {
        // Validate that container is a Question type
        Util::subclassOf($container, Question::class, true);

        // A collection
        if (!is_array($value)) {
            return false;
        }

        if (!count($value)) {
            return false;
        }

        return $this->validateObjectCollection(
            $value,
            $this->getQuestionAnswerValidator()
        );
    }
}
