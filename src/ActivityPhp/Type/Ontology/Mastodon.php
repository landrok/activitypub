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
 * \ActivityPhp\Type\Ontology\Mastodon contains Mastodon dialects
 *
 * @since 0.6.0
 */
abstract class Mastodon extends OntologyBase
{
    /**
     * A definition of Mastodon's dialect to overload Activity Streams
     * vocabulary.
     *
     * @var array
     */
    protected static $definitions = [
        'Hashtag' => [
            'href'
        ],
        'Note' => [
            'atomUri',
            'inReplyToAtomUri',
            'sensitive',
        ],
        'Person' => [
            'devices',
            'discoverable',
            'featured',
            'featuredTags',
            'indexable',
            'manuallyApprovesFollowers',
            'memorial',
        ],
        'PropertyValue' => [
            'value'
        ],
        'Service' => [
            'devices',
            'discoverable',
            'featured',
            'featuredTags',
            'indexable',
            'key_id_url',
            'manuallyApprovesFollowers',
            'memorial',
            'private_key',
        ]
    ];
}
