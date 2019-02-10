---
layout: default
permalink: activitypub-server-usage.html
title: ActivityPub server usage in PHP
excerpt: How to use an ActivityPub server in PHP.
---

ActivityPub Server - Usage
==========================

Usage
-----

```php
use ActivityPub\Server;

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

A server instance can receive an array as first parameter.

```php
use ActivityPub\Server;

// Create a server instance with parameters
$server = new Server([
    'logger'   => [],
    'instance' => [],
    'cache'    => [],
]);
```

Each array defines a scope of configuration. 

________________________________________________________________________

### Logger parameters

Sometimes, for testing purpose, it may be suitable to block log output.

**driver**

```php
use ActivityPub\Server;

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


** stream **

The default output for logging message is 'php://stdout'.
You can pass a filename where logs will be put.

```php
use ActivityPub\Server;

// Put logs into a specific file
$server = new Server([
    'logger'   => [
        'stream' => '/var/log/activitypub/server.log'    
    ],
]);
```


** channel **

The default channel is 'global'. in order to customize, you can pass a
channel parameter.

```php
use ActivityPub\Server;

// Put logs with a specific channel
$server = new Server([
    'logger'   => [
        'channel' => 'ap_channel'    
    ],
]);
```



________________________________________________________________________                


### Instance parameters


**hostname**

The default hostname is `localhost`. If you want to be reachable from
a network you may pass a custom hostname.

```php
use ActivityPub\Server;

$server = new Server([
    'instance'   => [
        'hostname' => 'activitypub.example.org'    
    ],
]);
```

**debug**

For testing purpose, you may use HTTP scheme instead of HTTPS.
The debug parameter is made for that.

```php
use ActivityPub\Server;

$server = new Server([
    'instance'   => [
        'debug' => true  
    ],
]);
```

For security purpose, the default value is `false`.

________________________________________________________________________


### Cache parameters


**type**

The default type of cache is `filesystem`. Cache is actived by default.


**enabled**

You can disable caching objects with `enabled` parameter.

```php
use ActivityPub\Server;

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
use ActivityPub\Server;

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
use ActivityPub\Server;

$server = new Server([
    'cache'   => [
        'ttl' => 60 // Set to 60 seconds  
    ],
]);
```

________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/docs/server-usage.md{% endcapture %}
{% include edit-doc-link.html %}
