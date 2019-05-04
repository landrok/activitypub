---
layout: default
permalink: activitypub-server-usage.html
title: ActivityPub server usage in PHP
excerpt: How to use an ActivityPub server in PHP.
---

ActivityPub Server - Usage
==========================

*Server features are still a WIP.*

Usage
-----

```php
use ActivityPhp\Server;

// Create a server instance
$server = new Server();

```

It's a very minimalistic example of use. With this configuration, an 
instance can only request for public activities and can't receive some 
data.

As you could imagine, a server instance, to be fully-functional, must 
accept some parameters.

Server parameters
-----------------

A server instance can receive an array of configurations as first 
parameter.

```php
use ActivityPhp\Server;

// Create a server instance with parameters
$server = new Server([
    'logger'   => [],
    'instance' => [],
    'cache'    => [],
    'http'     => [],
    'dialects' => [],
]);
```

Each array defines a scope of configuration. 

________________________________________________________________________

### Logger parameters

Sometimes, for testing purpose, it may be suitable to block log output.

**driver**

```php
use ActivityPhp\Server;

// Create a server instance with no log output
$server = new Server([
    'logger'   => [
        'driver' => '\Psr\Log\NullLogger'    
    ],
]);
```

If you have specific needs (Storing logs into a database, for 
instance), you may pass a custom logger driver. As it implements 
`Psr\Log\LoggerInterface`, you may pass any custom logger.

By default, the driver is [Monolog\Logger](https://github.com/Seldaek/monolog).


**stream**

The default output for logging message is `php://stdout`.
You can pass a filename where logs will be put.

```php
use ActivityPhp\Server;

// Put logs into a specific file
$server = new Server([
    'logger'   => [
        'stream' => '/var/log/activitypub/server.log'    
    ],
]);
```


**channel**

The default channel is `global`. in order to customize, you can pass a
channel parameter.

```php
use ActivityPhp\Server;

// Put logs with a specific channel
$server = new Server([
    'logger'   => [
        'channel' => 'ap_channel'    
    ],
]);
```


________________________________________________________________________                


### Instance parameters


**host**

The default hostname is `localhost`. If you want to be reachable from
a network you may pass a custom hostname.

```php
use ActivityPhp\Server;

$server = new Server([
    'instance'   => [
        'host' => 'activitypub.example.org'    
    ],
]);
```

**port**

The default port is `443`. If you want to customize it, you may pass a
port parameter.

```php
use ActivityPhp\Server;

$server = new Server([
    'instance'   => [
        'port' => 8000   
    ],
]);
```

Note: this does not seem to be supported by all ActivityPub servers of
the federation.


**debug**

For testing purpose, you may use HTTP scheme instead of HTTPS.
The debug parameter is made for that.

```php
use ActivityPhp\Server;

$server = new Server([
    'instance'   => [
        'debug' => true  
    ],
]);
```

For security purpose, the default value is `false`.

________________________________________________________________________                


### HTTP parameters


**timeout**

The default timeout for HTTP requests is `10s`..

```php
use ActivityPhp\Server;

// Increase HTTP request timeout to 20 seconds 
$server = new Server([
    'http'   => [
        'timeout' => '20'    
    ],
]);
```

________________________________________________________________________


### Cache parameters


**type**

The default type of cache is `filesystem`. Cache is actived by default.


**enabled**

You can disable caching objects with `enabled` parameter.

```php
use ActivityPhp\Server;

$server = new Server([
    'cache'   => [
        'enabled' => false
    ],
]);
```

**stream**

By default, it stores cache files in the common working directory (See
[PHP getcwd() manual](http://php.net/manual/en/function.getcwd.php)). 

You can customize where cache files are stored with the `stream` 
parameter.


```php
use ActivityPhp\Server;

$server = new Server([
    'cache'   => [
        'stream' => '/mycustom/directory'    
    ],
]);
```

**ttl**

The Time To Live (TTL) of an item is the amount of time in seconds 
between when that item is stored and it is considered stale.

The default value is 3600.

```php
use ActivityPhp\Server;

$server = new Server([
    'cache'   => [
        'ttl' => 60 // Set to 60 seconds  
    ],
]);
```

________________________________________________________________________


### Dialects parameters

Dialect parameter has a special role.

It defines and load ActivityPub types and properties which are not 
defined in the standard protocol.


**type**

This parameter is a 3-levels array containing:

`DialectName => Types => Properties`

**example**

In this example, we define 2 dialects with 1 type for each.

The first type extends ActivityPub `Person` type with an `uuid` property.
The second type creates a new type `HashTag` with 2 properties, `uuid` 
and `name`.

```php
use ActivityPhp\Server;

$dialect1 = [
    'Person' => ['uuid'],
];

$dialect2 = [
    'HashTag' => ['uuid', 'name'],
];

$server = new Server([
    'dialects'   => [
        'dialect1' => $dialect1,
        'dialect2' => $dialect2,
    ],
]);
```

After this loading, the new types and properties are usable.


```php
use ActivityPhp\Type;

$hashtag = Type::create([
    'type' => 'HashTag',
    'uuid' => 42,
    'name' => 'myTag'
];

$person  = Type::create([
    'type' => 'Person',
    'uuid' => 42
];
```

[More about dialects management]({{ site.doc_baseurl }}/activitypub-dialects-management.html)

[An example of Peertube's dialect implementation]({{ site.doc_baseurl }}/fetch-peertube-outbox-activities-using-dialects.html)

________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/server/server-usage.md{% endcapture %}
{% include edit-doc-link.html %}
