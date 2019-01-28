<?php

/*
 * This script emulates a WebFinger response.
 * It returns expected responses from an ActivityPub peer.
 */

if (!isset($_SERVER['QUERY_STRING'])) {
    response400();
}

$handle = str_replace('resource=acct:', '', $_SERVER['QUERY_STRING']);
$preferredUsername = substr($handle, 0, strpos($handle, '@'));

header('Content-Type: application/jrd+json');

switch ($handle) {
    case 'bob@' . $_SERVER['HTTP_HOST']:
        echo json_encode([
                'subject' => 'acct:' . $handle,
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
        break;
    case 'bob-subject-array@' . $_SERVER['HTTP_HOST']:
        echo json_encode([
                'subject' => ['acct:' . $handle],
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
        break;
    case 'bob-malformed-aliases@' . $_SERVER['HTTP_HOST']:
        echo json_encode([
                'subject' => ['acct:' . $handle],
                'aliases' => [
                    ['http//' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername],
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
        break;
    case 'bob-missing-links@' . $_SERVER['HTTP_HOST']:
        echo json_encode([
                'subject' => 'acct:' . $handle,
                'aliases' => [
                    'http//' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
                ],
            ]
            , JSON_PRETTY_PRINT
        );
        break;
    case 'bob-links-arrays@' . $_SERVER['HTTP_HOST']:
        echo json_encode([
                'subject' => ['acct:' . $handle],
                'aliases' => [
                    'http//' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
                ],
                'links' => [
                    'href' => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername
                ]
            ]
            , JSON_PRETTY_PRINT
        );
        break;
    case 'bob-links-missing-rel@' . $_SERVER['HTTP_HOST']:
        echo json_encode([
                'subject' => ['acct:' . $handle],
                'aliases' => [
                    'http//' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
                ],
                'links' => [
                    [
                        'type' => 'application/activity+json',
                        'href' => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername
                    ]
                ]
            ]
            , JSON_PRETTY_PRINT
        );
        break;
    default:
        response404();
        break;
}
        
