ActivityPub
===========

[![Build Status](https://travis-ci.org/landrok/activitypub.svg?branch=master)](https://travis-ci.org/landrok/activitypub)
[![Test Coverage](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/test_coverage)](https://codeclimate.com/github/landrok/activitypub/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/maintainability)](https://codeclimate.com/github/landrok/activitypub/maintainability)

ActivityPub is an implementation of ActivityPub layers in PHP.

It provides two layers:

- A __client to server protocol__, or "Social API"
    This protocol permits a client to act on behalf of a user.
- A __server to server protocol__, or "Federation Protocol"
    This protocol is used to distribute activities between actors on different servers, tying them into the same social graph. 

As the two layers are implemented, it aims to be an ActivityPub conformant Federated Server

All normalized types are implemented too. If you need to create a new
one, just extend existing types.

Table of contents
=================

- [Install](#install)
- [Requirements](#requirements)
- [Features](#features)
    - [ActivityStreams Core Types](#activitystreams-core-types)
    - [ActivityStreams Extended Types](#activitystreams-extended-types)
- [Usage](#usage)

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

Usage
-----

### Hydrate an extended type

```php
use ActivityPub\Type\Extended\Object\Note;

$note = new Note();
$note->id = 'https://example.com/notes/1';
```

You could do the same with all core types.

### Extend a core type

```php
use ActivityPub\Type\Extended\Object\Note;

class MyNote extends Note
{
    // Override basic type
    protected $type = 'CustomNote';

    // Custom property
    protected $myProperty;
}

$note = new MyNote();
$note->id = 'https://example.com/custom-notes/1';
$note->myProperty = 'Custom Value';
```

There are 3 equivalent ways for setting a property:

```php

$note = new Note();

$note->id = 'https://example.com/custom-notes/1';
$note->set('id', 'https://example.com/custom-notes/1');
$note->setId('https://example.com/custom-notes/1');

```

There are 3 equivalent ways for getting a property:

```php

$note = new Note();

// each method returns same value
echo $note->id;
echo $note->get('id');
echo $note->getId();

```

You can check that a property is defined with `has()` method:

```php

$note = new Note();

echo $note->has('id'); // Returns true
```

Extending types preserves benefits of getters, setters and 
their validators.

________________________________________________________________________

### Add custom validators for objects attributes

There is 2 possible cases:

1. Usage of custom attributes
2. Override ActivityPub attribute validation

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
