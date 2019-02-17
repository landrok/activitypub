<?php

/*
 * This script emulates an ActivityPhp response to a profile request.
 * It returns expected responses from an ActivityPhp peer.
 */

$validAccount = 'bob@' . $_SERVER['HTTP_HOST'];
$preferredUsername = substr($validAccount, 0, strpos($validAccount, '@'));

header('Content-Type: application/jrd+json');
echo json_encode([
        'id'   => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
        'type' => 'OrderedCollectionPage',
        'orderedItems' => [
            [
                'id' => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername . '/activities/1',
                'type' => 'Create',
                'object' => [
                    'id' => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername . '/notes/1',
                    'type' => 'Note',
                    'content' => 'This is a note',
                    'name' => 'Note n°1'
                ]
            ],
        ],
    ]
    , JSON_PRETTY_PRINT
);
