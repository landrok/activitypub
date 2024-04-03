ActivityPhp
===========

[![Build Status](https://github.com/landrok/activitypub/workflows/tests/badge.svg)](https://github.com/landrok/activitypub/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/maintainability)](https://codeclimate.com/github/landrok/activitypub/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/test_coverage)](https://codeclimate.com/github/landrok/activitypub/test_coverage)

ActivityPhp is an implementation of ActivityPub layers in PHP.

It provides two layers:

- A __client to server protocol__, or "Social API"
    This protocol permits a client to act on behalf of a user.
- A [__server to server protocol__](#server), or "Federation Protocol"
    This protocol is used to distribute activities between actors on different servers, tying them into the same social graph.

As the two layers are implemented, it aims to be an ActivityPub conformant Federated Server

All normalized types are implemented too. If you need to create a new
one, just extend existing types.

[See the full documentation](https://landrok.github.io/activitypub) or
an overview below.

Table of contents
=================

- [Install](#install)
- [Requirements](#requirements)
- [ActivityStreams Core Types](#activitystreams-core-types)
- [ActivityStreams Extended Types](#activitystreams-extended-types)
- [Types](#types)
    - [Type factory](#type-factory)
    - [Properties names](#properties-names)
    - [All properties and their values](#all-properties-and-their-values)
    - [Set several properties](#set-several-properties)
    - [Get a property](#get-a-property)
    - [Set a property](#set-a-property)
    - [Check if property exists](#check-if-property-exists)
    - [Create a copy](#create-a-copy)
    - [Use native types](#use-native-types)
    - [Use your own extended types](#use-your-own-extended-types)
    - [Create your own property validator](#create-your-own-property-validator)
    - [Dialects management](https://landrok.github.io/activitypub/activitypub-dialects-management.html)
- [Server](#server)
    - [Server configuration](https://landrok.github.io/activitypub/activitypub-server-usage.html)
    - [Verify HTTP signatures](https://landrok.github.io/activitypub/activitypub-server-verify-http-signatures.html)
    - [WebFinger](#webfinger)
    - [WebFinger::toArray()](#webfingertoarray)
    - [WebFinger::getSubject()](#webfingergetsubject)
    - [WebFinger::getProfileId()](#webfingergetprofileid)
    - [WebFinger::getHandle()](#webfingergethandle)
    - [WebFinger::getAliases()](#webfingergetaliases)
    - [WebFinger::getLinks()](#webfingergetlinks)

________________________________________________________________________

Requirements
------------

- Supports PHP 7.4 | 8.0+

________________________________________________________________________

Install
-------

```sh
composer require landrok/activitypub
```

________________________________________________________________________

ActivityStreams Core Types
--------------------------

All core types are provided:

```php
use ActivityPhp\Type\Core\Activity;
use ActivityPhp\Type\Core\Collection;
use ActivityPhp\Type\Core\CollectionPage;
use ActivityPhp\Type\Core\IntransitiveActivity;
use ActivityPhp\Type\Core\Link;
use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Core\OrderedCollection;
use ActivityPhp\Type\Core\OrderedCollectionPage;
```

________________________________________________________________________

ActivityStreams Extended Types
------------------------------

All extended types are provided:

### Actor types

```php
use ActivityPhp\Type\Extended\Actor\Application;
use ActivityPhp\Type\Extended\Actor\Group;
use ActivityPhp\Type\Extended\Actor\Organization;
use ActivityPhp\Type\Extended\Actor\Person;
use ActivityPhp\Type\Extended\Actor\Service;
```

### Activity types

```php
use ActivityPhp\Type\Extended\Activity\Accept;
use ActivityPhp\Type\Extended\Activity\Add;
use ActivityPhp\Type\Extended\Activity\Announce;
use ActivityPhp\Type\Extended\Activity\Arrive;
use ActivityPhp\Type\Extended\Activity\Block;
use ActivityPhp\Type\Extended\Activity\Create;
use ActivityPhp\Type\Extended\Activity\Delete;
use ActivityPhp\Type\Extended\Activity\Dislike;
use ActivityPhp\Type\Extended\Activity\Flag;
use ActivityPhp\Type\Extended\Activity\Follow;
use ActivityPhp\Type\Extended\Activity\Ignore;
use ActivityPhp\Type\Extended\Activity\Invite;
use ActivityPhp\Type\Extended\Activity\Join;
use ActivityPhp\Type\Extended\Activity\Leave;
use ActivityPhp\Type\Extended\Activity\Like;
use ActivityPhp\Type\Extended\Activity\Listen;
use ActivityPhp\Type\Extended\Activity\Move;
use ActivityPhp\Type\Extended\Activity\Offer;
use ActivityPhp\Type\Extended\Activity\Question;
use ActivityPhp\Type\Extended\Activity\Read;
use ActivityPhp\Type\Extended\Activity\Reject;
use ActivityPhp\Type\Extended\Activity\Remove;
use ActivityPhp\Type\Extended\Activity\TentativeAccept;
use ActivityPhp\Type\Extended\Activity\TentativeReject;
use ActivityPhp\Type\Extended\Activity\Travel;
use ActivityPhp\Type\Extended\Activity\Undo;
use ActivityPhp\Type\Extended\Activity\Update;
use ActivityPhp\Type\Extended\Activity\View;
```

### Object types

```php
use ActivityPhp\Type\Extended\Object\Article;
use ActivityPhp\Type\Extended\Object\Audio;
use ActivityPhp\Type\Extended\Object\Document;
use ActivityPhp\Type\Extended\Object\Event;
use ActivityPhp\Type\Extended\Object\Image;
use ActivityPhp\Type\Extended\Object\Mention;
use ActivityPhp\Type\Extended\Object\Note;
use ActivityPhp\Type\Extended\Object\Page;
use ActivityPhp\Type\Extended\Object\Place;
use ActivityPhp\Type\Extended\Object\Profile;
use ActivityPhp\Type\Extended\Object\Relationship;
use ActivityPhp\Type\Extended\Object\Tombstone;
use ActivityPhp\Type\Extended\Object\Video;
```

________________________________________________________________________

Types
-----

### Type factory

You can instanciate ActivityStreams types using their short name.

```php
use ActivityPhp\Type;

$link = Type::create('Link');
$note = Type::create('Note');

```

Instanciating a type and setting properties is possible with the second
parameter.

```php
use ActivityPhp\Type;

$note = Type::create('Note', [
    'content' => 'A content for my note'
]);

```

Starting from an array with a 'type' key, it's even possible to directly
instanciate your type.

```php
use ActivityPhp\Type;

$array = [
    'type'    => 'Note',
    'content' => 'A content for my note'
];

$note = Type::create($array);

```
________________________________________________________________________

### Properties names

Whatever be your object or link, you can get all properties names with
`getProperties()` method.

```php
use ActivityPhp\Type;

$link = Type::create('Link');

print_r(
    $link->getProperties()
);
```

Would output something like:

```
Array
(
    [0] => type
    [1] => id
    [2] => name
    [3] => nameMap
    [4] => href
    [5] => hreflang
    [6] => mediaType
    [7] => rel
    [8] => height
    [9] => preview
    [10] => width
)
```

________________________________________________________________________

### All properties and their values

In order to dump all properties and associated values, use `toArray()`
method.

```php
use ActivityPhp\Type;

$link = Type::create('Link');
$link->setName('An example');
$link->setHref('http://example.com');

print_r(
    $link->toArray()
);
```

Would output something like:

```
Array
(
    [type] => Link
    [name] => An example
    [href] => http://example.com
)
```

________________________________________________________________________

### Get a property

There are 3 equivalent ways to get a value.

```php
use ActivityPhp\Type;

$note = Type::create('Note');

// Each method returns the same value
echo $note->id;
echo $note->get('id');
echo $note->getId();
```

________________________________________________________________________

### Set a property

There are 3 equivalent ways to set a value.

```php
use ActivityPhp\Type;

$note = Type::create('Note');

$note->id = 'https://example.com/custom-notes/1';
$note->set('id', 'https://example.com/custom-notes/1');
$note->setId('https://example.com/custom-notes/1');

```

Whenever you assign a value, the format of this value is checked.

This action is made by a validator. If rules are not respected an
Exception is thrown.

When a property does not exist, an Exception is thrown in strict mode.
You can define 3 different behaviours:

- throw an exception (default=strict)
- ignore property (ignore)
- set property (include)

```php
use ActivityPhp\Type;
use ActivityPhp\Type\TypeConfiguration;

$note = Type::create('Note');

// Ignore mode
TypeConfiguration::set('undefined_properties', 'ignore');
$note->undefinedProperty = 'https://example.com/custom-notes/1';
echo $note->undefinedProperty; // null

// Include mode
TypeConfiguration::set('undefined_properties', 'include');
$note->undefinedProperty = 'https://example.com/custom-notes/1';
echo $note->undefinedProperty; // https://example.com/custom-notes/1

// Strict mode
TypeConfiguration::set('undefined_properties', 'strict');
$note->undefinedProperty = 'https://example.com/custom-notes/1'; // Exception

```

________________________________________________________________________

### Set several properties

With __Type factory__, you can instanciate a type and set several
properties.

```php
use ActivityPhp\Type;

$note = Type::create('Note', [
    'id'   => 'https://example.com/custom-notes/1',
    'name' => 'An important note',
]);

```
________________________________________________________________________

### Create a copy

Sometimes you may use a copy in order not to affect values of the
original type.


```php
use ActivityPhp\Type;

$note = Type::create('Note', ['name' => 'Original name']);

$copy = $note->copy()->setName('Copy name');

echo $copy->name; // Copy name
echo $note->name; // Original name
```

You can copy and chain methods to affect only values of the copied type.

________________________________________________________________________

### Check if a property exists

```php
use ActivityPhp\Type;

$note = Type::create('Note');

echo $note->has('id'); // true
echo $note->has('anotherProperty'); // false

```

________________________________________________________________________

### Use native types

All core and extended types are used with a classic instanciation.

```php
use ActivityPhp\Type\Extended\Object\Note;

$note = new Note();
```

Same way with Type factory:

```php
use ActivityPhp\Type;

$note = Type::create('Note');
```

________________________________________________________________________


### Use your own extended types

If you need some custom attributes, you can extend predefined types.

- Create your custom type:
```php
use ActivityPhp\Type\Extended\Object\Note;

class MyNote extends Note
{
    // Override basic type
    protected $type = 'CustomNote';

    // Custom property
    protected $myProperty;
}
```

There are 2 ways to instanciate a type:

- A classic PHP call:

```php
$note = new MyNote();
$note->id = 'https://example.com/custom-notes/1';
$note->myProperty = 'Custom Value';

echo $note->getMyProperty(); // Custom Value
```

- With the Type factory:

```php
use ActivityPhp\Type;

$note = Type::create('MyNote', [
    'id' => 'https://example.com/custom-notes/1',
    'myProperty' => 'Custom Value'
]);
```

Extending types preserves benefits of getters, setters and
their validators.

________________________________________________________________________


### Create your own property validator

Use a custom property validator when you define custom attributes or
when you want to override ActivityPub attribute default validation.

Regarding to previous example with a custom attribute `$myProperty`, if
you try to set this property, it would be done without any check on
values you're providing.

You can easily cope with that implementing a custom validator using
`Validator`.

```php
use ActivityPhp\Type\ValidatorInterface;
use ActivityPhp\Type\Validator;

// Create a custom validator that implements ValidatorInterface
class MyPropertyValidator implements ValidatorInterface
{
    // A public validate() method is mandatory
    public function validate($value, $container)
    {
        return true;
    }
}

// Attach this custom validator to a property
Validator::add('myProperty', MyPropertyValidator::class);

// Now all values are checked with the validate() method
// 'myProperty' is passed to the first argument
// $note is passed to the second one.

$note->myProperty = 'Custom Value';

```

An equivalent way is to use Type factory and `addValidator()` method:

```php
use ActivityPhp\Type;

// Attach this custom validator to a property
Type::addValidator('myProperty', MyPropertyValidator::class);

```
________________________________________________________________________


Server
------

A server instance is an entry point of a federation.

Its purpose is to receive, send and forward activities appropriately.

A minimal approach is:

```php
use ActivityPhp\Server;

$server = new Server();
```

For more configuration parameters, [See the full documentation](https://landrok.github.io/activitypub)

### WebFinger

WebFinger is a protocol that allows for discovery of information about
people.

Given a handle, ActivityPub instances can discover profiles using this
protocol.

```php
use ActivityPhp\Server;

$server = new Server();

$handle = 'bob@example.org';

// Get a WebFinger instance
$webfinger = $server->actor($handle)->webfinger();
```

In this implementation, we can use an Object Identifier (URI) instead of
a WebFinger handle.


```php
use ActivityPhp\Server;

$server = new Server();

$handle = 'https://example.org/users/bob';

// Get a WebFinger instance
$webfinger = $server->actor($handle)->webfinger();
```
________________________________________________________________________

### WebFinger::toArray()

Get all WebFinger data as an array.

```php
use ActivityPhp\Server;

$server = new Server();

$handle = 'bob@example.org';

// Get a WebFinger instance
$webfinger = $server->actor($handle)->webfinger();

// Dumps all properties
print_r($webfinger->toArray());

// A one line call
print_r(
    $server->actor($handle)->webfinger()->toArray()
);
```

Would output something like:

```
Array
(
    [subject] => acct:bob@example.org
    [aliases] => Array
        (
            [0] => http://example.org/users/bob
        )
    [links] => Array
        (
            [0] => Array
                (
                    [rel] => self
                    [type] => application/activity+json
                    [href] => http://example.org/users/bob
                )
        )
)
```
________________________________________________________________________

### WebFinger::getSubject()

Get a WebFinger resource.

```php
echo $webfinger->getSubject();

// Would output 'acct:bob@example.org'
```
________________________________________________________________________

### WebFinger::getProfileId()

Get ActivityPub object identifier (URI).

```php
echo $webfinger->getProfileId();

// Would output 'http://example.org/users/bob'
```
________________________________________________________________________

### WebFinger::getHandle()

Get a profile handle.

```php
echo $webfinger->getHandle();

// Would output 'bob@example.org'
```
________________________________________________________________________

### WebFinger::getAliases()

Get all aliases entries for this profile.

```php
print_r(
    $webfinger->getAliases()
);
```

Would output something like:

```
Array
(
    [0] => http://example.org/users/bob
)
```

________________________________________________________________________

### WebFinger::getLinks()

Get all links entries for this profile.

```php

print_r(
    $webfinger->getLinks()
);

```

Would output something like:

```
Array
(
    [0] => Array
        (
            [rel] => self
            [type] => application/activity+json
            [href] => http://example.org/users/bob
        )
)

```
________________________________________________________________________


More
----

- [See the full documentation](https://landrok.github.io/activitypub/)

- To discuss new features, make feedback or simply to share ideas, you
  can contact me on Mastodon at [https://phpc.social/@landrok](https://phpc.social/@landrok)
- [ActivityPub](https://www.w3.org/TR/activitypub/)
- [ActivityStreams 2.0](https://www.w3.org/TR/activitystreams-core/)
- [JSON-LD](https://www.w3.org/TR/json-ld/)
- [WebFinger](https://tools.ietf.org/html/rfc7033)
