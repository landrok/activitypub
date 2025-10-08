---
layout: default
permalink: activitypub-server-implementation.html
title: ActivityPub server implementation in PHP
excerpt: How to implement an ActivityPub server in PHP.
---

Implement an ActivityPub server
===============================

Usage
-----

```php
use ActivityPhp\Server;

// Create a server instance
$server = new Server();

// Post an activity
$response = $server->outbox("actor@example.com")->post([
    "@context" => "https://www.w3.org/ns/activitystreams",
    "type" => "Note",
    "content" => "This is a note",
    "published" => "2015-02-10T15:04:55Z",
    "to" => ["https =>//example.org/~john/"],
    "cc" => [
        "https =>//example.com/~erik/followers",
        "https =>//www.w3.org/ns/activitystreams#Public"
    ]
]);

```

`$response` is a `ActivityPub\Server\Response` class;


Response methods
----------------

getStatus

getCode

getLocation

hasError


Server parameters
-----------------

A server instance can receive a configuration array as first parameter.

```php
use ActivityPhp\Server;

// Create a server instance
$server = new Server([
    'logger'   => [],
    'instance' => [],
    'http'     => [],
    'database' => [],
    'cache'    => [],
    'jobs'     => []
]);
```

________________________________________________________________________

### logger parameters

- channel: A string defining a log channel. Default is 'global'

- loglevel: Minimal loglevel. This is only relevant for PSR-3 compatible loggers
    that support log levels (like Monolog)

- stream: Where to put log messages. Default is 'php://stdout'.
        It can be a filename.

________________________________________________________________________


### instance parameters

- hostname
- debug: boolean.
    default = false


________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/activitystreams-types.md{% endcapture %}
{% include edit-doc-link.html %}
