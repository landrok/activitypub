<?php

/*
 * This script emulates an ActivityPub response to a profile request.
 * It returns a malformed profile.
 */

header('Content-Type: application/jrd+json');
echo json_encode([
        'id'   => 'http://' . $_SERVER['HTTP_HOST'] . '/accounts/' . $preferredUsername,
    ]
    , JSON_PRETTY_PRINT
);
