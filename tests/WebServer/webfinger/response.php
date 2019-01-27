<?php

/*
 * This script emulates a WebFinger response.
 * It returns expected responses from an ActivityPub peer.
 */

$validAccount = 'bob@' . $_SERVER['HTTP_HOST'];
$preferredUsername = substr($validAccount, 0, strpos($validAccount, '@'));

if (!isset($_SERVER['QUERY_STRING'])) {
    response400();
}

if ($_SERVER['QUERY_STRING'] !== 'resource=acct:' . $validAccount) {
    response404();
}

header('Content-Type: tactac');
echo json_encode([
        'subject' => 'acct:' . $validAccount,
        'aliases' => [
            'http//' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
        ],
        'links' => [
            [
                'rel'  => 'self',
                'type' => 'application/activity+json',
                'href' => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername
            ]
        ]
    ]
    , JSON_PRETTY_PRINT
);
