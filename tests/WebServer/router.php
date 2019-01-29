<?php

/*
 * This script is called by PHP built-in web server during tests only.
 * Dispatch request to expected responses from an ActivityPub peer.
 */
 
$request = [
  'path'   => $_SERVER['SCRIPT_NAME'],
  'host'   => $_SERVER['SERVER_NAME'],
  'method' => $_SERVER['REQUEST_METHOD']
];

switch ($request['path'])
{
    case '/.well-known/webfinger':
        $route = __DIR__ . '/webfinger/response.php';
        break;
    case strpos($request['path'], '/accounts') == 0:
        $route = __DIR__ . $request['path'] . '.php';
        break;
    default:
        $route = __DIR__ . '/404.php';
        break;
}

if (file_exists($route)) {
    include $route;
    exit(0);
}

response404();

function response404()
{
    header("HTTP/1.1 404 Not Found");
    echo '<h1>Not Found</h1>';
    die();
}

function response400()
{
    header("HTTP/1.1 400 Bad Request");
    echo '<h1>Bad Request</h1>';
    die();
}
