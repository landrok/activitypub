<?php

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
 * \ActivityPhp\Type\Ontology\Peertube contains Peertube dialects
 * 
 * @since 0.4.0
 */
abstract class Peertube extends OntologyBase
{
    /**
     * A definition of peertube's dialect to overload Activity Streams
     * vocabulary.
     * 
     * @var array
     */
    protected static $definitions = [
        'Group'  => ['support'],
        'Video'  => [
            'uuid', 'category', 'language', 'views', 'sensitive', 
            'waitTranscoding', 'state', 'commentsEnabled', 'support', 
            'subtitleLanguage', 'likes', 'dislikes', 'shares', 
            'comments', 'licence', 
            'downloadEnabled', 'originallyPublishedAt'
        ],
        'Image'   => ['width', 'height'],
        'Link'    => ['fps', 'mimeType', 'size' ],
        'Hashtag' => ['type'],
        'Person|Group' => ['uuid', 'publicKey', 'playlists'],
    ];
}
