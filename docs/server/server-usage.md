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
    'logger'     => [],
    'instance'   => [],
    'cache'      => [],
    'http'       => [],
    'dialects'   => [],
    'ontologies' => [],
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

By default, the driver is `Psr\Log\NullLogger` which discards all log messages.
You can use any PSR-3 compatible logger such as [Monolog](https://github.com/Seldaek/monolog).


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

**types**

This option tells the instance which behaviour when an unknown property
or an undefined type is encountered.

All federations (mastodon, peertube, pixelfed, etc...) are implementing
non standard activity streams properties and types.

By default, an instance is configured in `strict` mode. When a non-
standard type is encountered, if it's not defined as a dialect, it
throws an exception.

It can be blocking if you're working with many kinds of federations.

So, you may configure your instance with a less strict requirement in two
ways:

- `ignore` : non standard types and properties are ignored
- `include`: non standard types and properties are set and created on
the fly.


```php
use ActivityPhp\Server;

$server = new Server([
    'instance'   => [
        'types' => 'include'
    ],
]);
```


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

The default timeout for HTTP requests is `10s`.

```php
use ActivityPhp\Server;

// Increase HTTP request timeout to 20 seconds
$server = new Server([
    'http'   => [
        'timeout' => '20'
    ],
]);
```

**agent**

When talking to other federated servers, an instance
uses the default user agent *ActivityPhp/x.y.z (+https://{host})*.

It can be customized with agent parameter.

```php
use ActivityPhp\Server;

$server = new Server([
    'http'   => [
        'agent' => 'MyCustomUserAgent'
    ],
]);
```

**retries** and **sleep**

Other federated servers might have some problems and responds with HTTP errors (5xx).

The server instance may retry to reach another instance.
By default, it will make 2 more attempts with 5 seconds between each before failing.

Setting to `-1` would make it endlessly attempt to transmit its message (Not recommended).
Setting to `0` would make it never retry to transmit its message.

```php
use ActivityPhp\Server;

// Setting to 5 maximum retries with 10 seconds between each
$server = new Server([
    'http'   => [
        'retries' => 5,
        'sleep'   => 10,
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

**pool**

ActivtyPhp comes with a default cache driver `\Symfony\Component\Cache\Adapter\FilesystemAdapter`.

There are 2 ways to override this driver with __pool__ parameter.

You can pass a class name that will be automatically instanciated.

```php
use ActivityPhp\Server;

$server = new Server([
    'cache'   => [
        'pool' => 'Symfony\Component\Cache\Adapter\ArrayAdapter'
    ],
]);
```

If you need a cache pool with custom configuration, you can pass an
instanciated pool.

```php
use ActivityPhp\Server;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

$server = new Server([
    'cache'   => [
        'pool' => new ArrayAdapter()
    ],
]);
```

The given pool must implement `Psr\Cache\CacheItemPoolInterface`.

__Note__

All [Symfony cache adapters](https://symfony.com/doc/current/components/cache.html#available-cache-adapters)
are supplied as dependencies.

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

________________________________________________________________________


### Ontologies parameters

Ontology parameter has a special role.

It defines and load ActivityPub types and properties which are not
defined in the standard protocol but that are commonly used by Federated
applications. It permits that your application will be compatible with
other federations.

**type**

This parameter is a 1 or 2-levels array containing:

`OntologyName`

or

`OntologyName` => `CustomOntologyClassName`

**examples**

In this example, we load Peertube's ontology.

It's a packaged ontology.

```php
$server = new Server([
    'ontologies'   => [
        'peertube'
    ],
]);
```

After this loading, the new types and properties are usable.

In this second example, we load all predefined (packaged) ontologies.

```php
$server = new Server([
    'ontologies'   => [
        '*'
    ],
]);
```

You can create and define your custom ones and load them.

```php
$server = new Server([
    'ontologies'   => [
        'my-ontology' => MyClass::class
    ],
]);
```

For more informations about creating your custom ontology, see the
dedicated manual
[ontologies management manual]({{ site.doc_baseurl }}/activitypub-ontologies-management.html)


Obviously, you can load several ontologies.

```php
$server = new Server([
    'ontologies'   => [
        'peertube',
        'my-ontology' => MyClass::class
    ],
]);
```

**supported ontologies**

Following ontologies are currently supported:

- peertube
- mastodon
- lemmy
- bookwyrm


This feature needs you. Contributions are very welcome.

[More about ontologies management]({{ site.doc_baseurl }}/activitypub-ontologies-management.html)

[An example of Peertube's ontology implementation]({{ site.doc_baseurl }}/fetch-peertube-outbox-activities-using-dialects.html)

________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/server/server-usage.md{% endcapture %}
{% include edit-doc-link.html %}
