<?php

/*
 * This script emulates an ActivityPhp response to a profile request.
 * It returns expected responses from an ActivityPhp peer.
 */

$validAccount = 'bob@' . $_SERVER['HTTP_HOST'];
$preferredUsername = substr($validAccount, 0, strpos($validAccount, '@'));
$publicKey = file_get_contents(dirname(__DIR__) . '/keys/public.pem');

header('Content-Type: application/jrd+json');
echo json_encode([
        'id'   => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
        'type' => 'Person',
        'preferredUsername' => $preferredUsername,
        'outbox' => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername . '/outbox',
        'inbox' => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername . '/inbox',
        'publicKey' => [
            "id"           => "https://my-example.com/actor#main-key",
            "owner"        => "https://my-example.com/actor",
            'publicKeyPem' => $publicKey,
        
        ]
    ]
    , JSON_PRETTY_PRINT
);
