---
layout: default
---

[![Build Status](https://travis-ci.org/landrok/activitypub.svg?branch=master)](https://travis-ci.org/landrok/activitypub)
[![Test Coverage](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/test_coverage)](https://codeclimate.com/github/landrok/activitypub/test_coverage)

ActivityPub is an implementation of ActivityPub layers in PHP.

It provides two layers:

- A __client to server protocol__, or "Social API"
    This protocol permits a client to act on behalf of a user.
- A __server to server protocol__, or "Federation Protocol"
    This protocol is used to distribute activities between actors on 
    different servers, tying them into the same social graph. 

As the two layers are implemented, it aims to be an ActivityPub 
conformant Federated Server

All [normalized types]({{ site.doc_baseurl }}/activitystreams-types.html)
are implemented too. If you need to create a new one, just extend 
existing implementations.

________________________________________________________________________

Basics
------

- [Install]({{ site.doc_baseurl }}/#install)
- [Requirements]({{ site.doc_baseurl }}/#requirements)
- [Types]({{ site.doc_baseurl }}/#types)
    - [Type factory]({{ site.doc_baseurl }}/#type-factory)
    - [Properties names]({{ site.doc_baseurl }}/#properties-names)
    - [All properties and their values]({{ site.doc_baseurl }}/#all-properties-and-their-values)
    - [Set several properties]({{ site.doc_baseurl }}/#set-several-properties)
    - [Get a property]({{ site.doc_baseurl }}/#get-a-property)
    - [Set a property]({{ site.doc_baseurl }}/#set-a-property)
    - [Check if property exists]({{ site.doc_baseurl }}/#check-if-property-exists)
    - [Create a copy]({{ site.doc_baseurl }}/#create-a-copy)
    - [Use native types]({{ site.doc_baseurl }}/#use-native-types)
    - [Use your own extended types]({{ site.doc_baseurl }}/#use-your-own-extended-types)
    - [Create your own property validator]({{ site.doc_baseurl }}/#create-your-own-property-validator)
- [Server](#server)
    - [WebFinger]({{ site.doc_baseurl }}/#webfinger)
    - [WebFinger::toArray()]({{ site.doc_baseurl }}/#webfinger-toarray)
    - [WebFinger::getSubject()]({{ site.doc_baseurl }}/#webfinger-getsubject)
    - [WebFinger::getProfileId()]({{ site.doc_baseurl }}/#webfinger-getprofileid)
    - [WebFinger::getHandle()]({{ site.doc_baseurl }}/#webfinger-gethandle)
    - [WebFinger::getAliases()]({{ site.doc_baseurl }}/#webfinger-getaliases)
    - [WebFinger::getLinks()]({{ site.doc_baseurl }}/#webfinger-getlinks)

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

print_r( $link->getProperties() );
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


### Check if property exists

```php
use ActivityPub\Type;

$note = Type::create('Note');

echo $note->has('id'); // true
echo $note->has('anotherProperty'); // false

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

### Use native types

All core and extended types can be used with a classic instanciation.

```php
use ActivityPub\Type\Extended\Object\Note;

$note = new Note();
```

Same way with the Type factory:

```php
use ActivityPub\Type;

$note = Type::create('Note');
```

________________________________________________________________________


### Use your own extended types

If you need some custom attributes, you can extend predefined types.

- Define your custom type:

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

Now that we know how to use types, let's see what types are implemented
and how to use them thanks to 
[the ActivityStreams Types manual]({{ site.doc_baseurl }}/activitystreams-types.html).

________________________________________________________________________


Server
------

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
WebFinger handle.


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
            [0] => http//example.org/users/bob
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

Get WebFinger resource.

```php

echo $webfinger->getSubject();

// Would return 'acct:bob@example.org'

```
________________________________________________________________________

### WebFinger::getProfileId()

Get ActivityPub object identifier (URI).

```php

echo $webfinger->getProfileId();

// Would return 'http://example.org/users/bob'

```
________________________________________________________________________

### WebFinger::getHandle()

Get profile handle.

```php

echo $webfinger->getHandle();

// Would return 'bob@example.org'

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
    [0] => http//example.org/users/bob
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



{% capture doc_url %}{{ site.doc_repository_url }}/index.md{% endcapture %}
{% include edit-doc-link.html %}
