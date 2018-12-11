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

use ActivityPub\Type\Util;
use ActivityPub\Type\ValidatorTools;

/**
 * \ActivityPub\Type\Validator\SummaryMapValidator is a dedicated
 * validator for summaryMap attribute.
 */
class SummaryMapValidator extends ValidatorTools
{
    /**
     * Validate a summaryMap attribute value
     * 
     * @param string  $value
     * @param mixed   $container An Object type
     * @return bool
     */
    public function validate($value, $container)
    {
        // Can be a JSON string
        if (is_string($value)) {
            $value = Util::decodeJson($value);
        }

        return $this->validateMap('summary', $value, $container);
    }
}
