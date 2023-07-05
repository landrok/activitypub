<?php

declare(strict_types=1);

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Ontology;

use ActivityPhp\Type\OntologyBase;

/**
 * \ActivityPhp\Type\Ontology\BookWyrm contains BookWyrm dialects
 *
 * @since 0.6.0
 */
abstract class BookWyrm extends OntologyBase
{
    /**
     * A definition of BookWyrm's dialect to overload Activity Streams
     * vocabulary.
     *
     * @var array
     */
    protected static $definitions = [
        'Person' => [
            'bookwyrmUser',
            'hideFollows',
            'icon',
        ],
    ];
}
