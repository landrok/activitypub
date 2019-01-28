<?php

/*
 * This script emulates a WebFinger response.
 * It returns expected responses from an ActivityPub peer.
 */

$validAccount = 'bob@' . $_SERVER['HTTP_HOST'];
$preferredUsername = substr($validAccount, 0, strpos($validAccount, '@'));

header('Content-Type: application/jrd+json');
echo json_encode([
        'id'   => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
        'type' => 'Person',
        'preferredUsername' => $preferredUsername,
    ]
    , JSON_PRETTY_PRINT
);
