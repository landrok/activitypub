---
layout: default
permalink: activitystreams-types.html
title: ActivityStreams 2.0 types in PHP
excerpt: Usage of ActivityStreams 2.0 types in PHP.
---

ActivityStreams types
=====================

ActivityStreams 2.0 defines a standardized collection of objects called
Vocabulary.

This collection is divided into 2 parts: Core Types and Extended Types.

Extended Types are divided into 3 parts: Actor, Activity and Object 
Types.

In this manual, we'll see how they are implemented in ActivityPub in 
PHP and how to use them.

________________________________________________________________________


- [ActivityStreams Core Types]({{ site.doc_baseurl }}/activitystreams-types.html#activitystreams-core-types)
- [ActivityStreams Extended Types]({{ site.doc_baseurl }}/activitystreams-types.html#activitystreams-extended-types)
    - [Actor Types]({{ site.doc_baseurl }}/activitystreams-types.html#actor-types)
    - [Activity Types]({{ site.doc_baseurl }}/activitystreams-types.html#activity-types)
    - [Object Types]({{ site.doc_baseurl }}/activitystreams-types.html#object-types)
- [Custom types]({{ site.doc_baseurl }}/activitystreams-types.html#custom-types)
- [Real world examples]({{ site.doc_baseurl }}/activitystreams-types.html#real-world-examples)

________________________________________________________________________

ActivityStreams Core Types
--------------------------

Core types are __Activity__, __Collection__, __CollectionPage__,
__IntransitiveActivity__, __Link__, __Object__, __OrderedCollection__
and __OrderedCollectionPage__.

They are all provided in the __ActivityPhp\Type\Core__ namespace.

```php
use ActivityPhp\Type\Core\Activity;
use ActivityPhp\Type\Core\Collection;
use ActivityPhp\Type\Core\CollectionPage;
use ActivityPhp\Type\Core\IntransitiveActivity;
use ActivityPhp\Type\Core\Link;
use ActivityPhp\Type\Core\ObjectType; // Object is a reserved keyword in PHP
use ActivityPhp\Type\Core\OrderedCollection;
use ActivityPhp\Type\Core\OrderedCollectionPage;
```

Instead of using them with namespace imports (use ...), you may wish to
call them by their short names with only one import using the *Type*
factory.

```php
use ActivityPhp\Type;

$activity              = Type::create('Activity');
$collection            = Type::create('Collection');
$collectionPage        = Type::create('CollectionPage');
$intransitiveActivity  = Type::create('IntransitiveActivity');
$link                  = Type::create('Link');
$object                = Type::create('ObjectType');
$object                = Type::create('Object');
$orderedCollection     = Type::create('OrderedCollection');
$orderedCollectionPage = Type::create('OrderedCollectionPage');
```

Note that the Object type can be called in 2 ways.

________________________________________________________________________

ActivityStreams Extended Types
------------------------------

All extended types are provided:

### Actor types

They are all provided in the 
__ActivityPhp\Type\Extended\Actor__ namespace.

```php
use ActivityPhp\Type\Extended\Actor\Application;
use ActivityPhp\Type\Extended\Actor\Group;
use ActivityPhp\Type\Extended\Actor\Organization;
use ActivityPhp\Type\Extended\Actor\Person;
use ActivityPhp\Type\Extended\Actor\Service;
```

Like Core types, you may wish to call them by their short names using 
the *Type* factory.

```php
use ActivityPhp\Type;

$application  = Type::create('Application');
$group        = Type::create('Group');
$organization = Type::create('Organization');
$person       = Type::create('Person');
$service      = Type::create('Service');
```

### Activity types

They are all provided in the 
__ActivityPhp\Type\Extended\Activity__ namespace.

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

As usual, you may wish to call them by their short names using the 
*Type* factory.

```php
use ActivityPhp\Type;

$accept          = Type::create('Accept');
$add             = Type::create('Add');
$announce        = Type::create('Announce');
$arrive          = Type::create('Arrive');
$block           = Type::create('Block');
$create          = Type::create('Create');
$delete          = Type::create('Delete');
$dislike         = Type::create('Dislike');
$flag            = Type::create('Flag');
$follow          = Type::create('Follow');
$ignore          = Type::create('Ignore');
$invite          = Type::create('Invite');
$join            = Type::create('Join');
$leave           = Type::create('Leave');
$like            = Type::create('Like');
$listen          = Type::create('Listen');
$move            = Type::create('Move');
$offer           = Type::create('Offer');
$question        = Type::create('Question');
$read            = Type::create('Read');
$reject          = Type::create('Reject');
$remove          = Type::create('Remove');
$tentativeAccept = Type::create('TentativeAccept');
$tentativeReject = Type::create('TentativeReject');
$travel          = Type::create('Travel');
$undo            = Type::create('Undo');
$update          = Type::create('Update');
$view            = Type::create('View');

```


### Object types

They are all provided in the 
__ActivityPhp\Type\Extended\Object__ namespace.

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

As usual, you may wish to call them by their short names using the 
*Type* factory.

```php
use ActivityPhp\Type;

$article      = Type::create('Article');
$audio        = Type::create('Audio');
$document     = Type::create('Document');
$event        = Type::create('Event');
$image        = Type::create('Image');
$mention      = Type::create('Mention');
$note         = Type::create('Note');
$page         = Type::create('Page');
$place        = Type::create('Place');
$profile      = Type::create('Profile');
$relationship = Type::create('Relationship');
$tombstone    = Type::create('Tombstone');
$video        = Type::create('Video');

```

________________________________________________________________________

Custom Types
------------

If you would like to create custom types, you may extend existing types.

An example is given in 
[this part of the manual]({{ site.doc_baseurl }}#use-your-own-extended-types).

________________________________________________________________________

Real world examples
-------------------

These examples are illustrating how to easily use implemented types to 
customize your models.

- [Fetch Peertube Outbox activities]({{ site.doc_baseurl }}/fetch-peertube-outbox-activities.html)


________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/activitystreams-types.md{% endcapture %}
{% include edit-doc-link.html %}
