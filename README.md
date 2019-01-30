ActivityPub
===========

[![Build Status](https://travis-ci.org/landrok/activitypub.svg?branch=master)](https://travis-ci.org/landrok/activitypub)
[![Maintainability](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/maintainability)](https://codeclimate.com/github/landrok/activitypub/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/test_coverage)](https://codeclimate.com/github/landrok/activitypub/test_coverage)

ActivityPub is an implementation of ActivityPub layers in PHP.

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
- [Server](#server)
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

- Supports PHP7+

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
use ActivityPub\Type\Core\Activity;
use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Core\CollectionPage;
use ActivityPub\Type\Core\IntransitiveActivity;
use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Core\OrderedCollection;
use ActivityPub\Type\Core\OrderedCollectionPage;
```

________________________________________________________________________

ActivityStreams Extended Types
------------------------------

All extended types are provided:

### Actor types

```php
use ActivityPub\Type\Extended\Actor\Application;
use ActivityPub\Type\Extended\Actor\Group;
use ActivityPub\Type\Extended\Actor\Organization;
use ActivityPub\Type\Extended\Actor\Person;
use ActivityPub\Type\Extended\Actor\Service;
```

### Activity types

```php
use ActivityPub\Type\Extended\Activity\Accept;
use ActivityPub\Type\Extended\Activity\Add;
use ActivityPub\Type\Extended\Activity\Announce;
use ActivityPub\Type\Extended\Activity\Arrive;
use ActivityPub\Type\Extended\Activity\Block;
use ActivityPub\Type\Extended\Activity\Create;
use ActivityPub\Type\Extended\Activity\Delete;
use ActivityPub\Type\Extended\Activity\Dislike;
use ActivityPub\Type\Extended\Activity\Flag;
use ActivityPub\Type\Extended\Activity\Follow;
use ActivityPub\Type\Extended\Activity\Ignore;
use ActivityPub\Type\Extended\Activity\Invite;
use ActivityPub\Type\Extended\Activity\Join;
use ActivityPub\Type\Extended\Activity\Leave;
use ActivityPub\Type\Extended\Activity\Like;
use ActivityPub\Type\Extended\Activity\Listen;
use ActivityPub\Type\Extended\Activity\Move;
use ActivityPub\Type\Extended\Activity\Offer;
use ActivityPub\Type\Extended\Activity\Question;
use ActivityPub\Type\Extended\Activity\Read;
use ActivityPub\Type\Extended\Activity\Reject;
use ActivityPub\Type\Extended\Activity\Remove;
use ActivityPub\Type\Extended\Activity\TentativeAccept;
use ActivityPub\Type\Extended\Activity\TentativeReject;
use ActivityPub\Type\Extended\Activity\Travel;
use ActivityPub\Type\Extended\Activity\Undo;
use ActivityPub\Type\Extended\Activity\Update;
use ActivityPub\Type\Extended\Activity\View;
```

### Object types

```php
use ActivityPub\Type\Extended\Object\Article;
use ActivityPub\Type\Extended\Object\Audio;
use ActivityPub\Type\Extended\Object\Document;
use ActivityPub\Type\Extended\Object\Event;
use ActivityPub\Type\Extended\Object\Image;
use ActivityPub\Type\Extended\Object\Mention;
use ActivityPub\Type\Extended\Object\Note;
use ActivityPub\Type\Extended\Object\Page;
use ActivityPub\Type\Extended\Object\Place;
use ActivityPub\Type\Extended\Object\Profile;
use ActivityPub\Type\Extended\Object\Relationship;
use ActivityPub\Type\Extended\Object\Tombstone;
use ActivityPub\Type\Extended\Object\Video;
```

________________________________________________________________________

Types
-----

### Type factory

You can instanciate ActivityStreams types using their short name.

```php
use ActivityPub\Type;

$link = Type::create('Link');
$note = Type::create('Note');

```

Instanciating a type and setting properties is possible with the second 
parameter.

```php
use ActivityPub\Type;

$note = Type::create('Note', [
    'content' => 'A content for my note'
]);

```

Starting from an array with a 'type' key, it's even possible to directly
instanciate your type.

```php
use ActivityPub\Type;

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
use ActivityPub\Type;

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
use ActivityPub\Type;

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
use ActivityPub\Type;

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
use ActivityPub\Type;

$note = Type::create('Note');

$note->id = 'https://example.com/custom-notes/1';
$note->set('id', 'https://example.com/custom-notes/1');
$note->setId('https://example.com/custom-notes/1');

```

Whenever you assign a value, the format of this value is checked.

This action is made by a validator. If rules are not respected an 
Exception is thrown.

________________________________________________________________________

### Set several properties

With __Type factory__, you can instanciate a type and set several 
properties.

```php
use ActivityPub\Type;

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
use ActivityPub\Type;

$note = Type::create('Note', ['name' => 'Original name']);

$copy = $note->copy()->setName('Copy name');

echo $copy->name; // Copy name
echo $note->name; // Original name
```

You can copy and chain methods to affect only values of the copied type.

________________________________________________________________________

### Check if a property exists

```php
use ActivityPub\Type;

$note = Type::create('Note');

echo $note->has('id'); // true
echo $note->has('anotherProperty'); // false

```

________________________________________________________________________

### Use native types

All core and extended types are used with a classic instanciation.

```php
use ActivityPub\Type\Extended\Object\Note;

$note = new Note();
```

Same way with Type factory:

```php
use ActivityPub\Type;

$note = Type::create('Note');
```

________________________________________________________________________


### Use your own extended types

If you need some custom attributes, you can extend predefined types.

- Create your custom type:
```php
use ActivityPub\Type\Extended\Object\Note;

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
use ActivityPub\Type;

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
use ActivityPub\Type\ValidatorInterface;
use ActivityPub\Type\Validator;

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
use ActivityPub\Type;

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
use ActivityPub\Server;

$server = new Server();
```

### WebFinger

WebFinger is a protocol that allows for discovery of information about
people.

Given a handle, ActivityPub instances can discover profiles using this
protocol.

```php
use ActivityPub\Server;

$server = new Server();

$handle = 'bob@example.org';

// Get a WebFinger instance
$webfinger = $server->actor($handle)->webfinger();
```

In this implementation, we can use an Object Identifier (URI) instead of
a WebFinger handle.


```php
use ActivityPub\Server;

$server = new Server();

$handle = 'https://example.org/users/bob';

// Get a WebFinger instance
$webfinger = $server->actor($handle)->webfinger();
```
________________________________________________________________________

### WebFinger::toArray()

Get all WebFinger data as an array.

```php
use ActivityPub\Server;

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
  can contact me on Mastodon at [https://cybre.space/@landrok](https://cybre.space/@landrok)
- [ActivityPub](https://www.w3.org/TR/activitypub/)
- [ActivityStreams 2.0](https://www.w3.org/TR/activitystreams-core/)
- [JSON-LD](https://www.w3.org/TR/json-ld/)
- [WebFinger](https://tools.ietf.org/html/rfc7033)
