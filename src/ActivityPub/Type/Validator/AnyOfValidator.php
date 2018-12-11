<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Validator;

use ActivityPub\Type\Extended\Activity\Question;
use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorTools;

/**
 * \ActivityPub\Type\Validator\AnyOfValidator is a dedicated
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

        // Can be a JSON string
        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

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
