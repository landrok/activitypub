---
layout: default
permalink: activitystreams-types.html
title: ActivityStreams 2.0 types in PHP
excerpt: Usage of ActivityStreams 2.0 types in PHP.
---

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

________________________________________________________________________

ActivityStreams Core Types
--------------------------

Core types are __Activity__, __Collection__, __CollectionPage__,
__IntransitiveActivity__, __Link__, __Object__, __OrderedCollection__
and __OrderedCollectionPage__.

They are all provided in the __ActivityPub\Type\Core__ namespace.

```php
use ActivityPub\Type\Core\Activity;
use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Core\CollectionPage;
use ActivityPub\Type\Core\IntransitiveActivity;
use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Core\ObjectType; // Object is a reserved keyword in PHP
use ActivityPub\Type\Core\OrderedCollection;
use ActivityPub\Type\Core\OrderedCollectionPage;
```

Instead of using them with namespace imports (use ...), you may wish to
call them by their short names with only one import using the *Type*
factory.

```php
use ActivityPub\Type;

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
__ActivityPub\Type\Extended\Actor__ namespace.

```php
use ActivityPub\Type\Extended\Actor\Application;
use ActivityPub\Type\Extended\Actor\Group;
use ActivityPub\Type\Extended\Actor\Organization;
use ActivityPub\Type\Extended\Actor\Person;
use ActivityPub\Type\Extended\Actor\Service;
```

Like Core types, you may wish to call them by their short names using 
the *Type* factory.

```php
use ActivityPub\Type;

$application  = Type::create('Application');
$group        = Type::create('Group');
$organization = Type::create('Organization');
$person       = Type::create('Person');
$service      = Type::create('Service');
```

### Activity types

They are all provided in the 
__ActivityPub\Type\Extended\Activity__ namespace.

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

As usual, you may wish to call them by their short names using the 
*Type* factory.

```php
use ActivityPub\Type;

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
__ActivityPub\Type\Extended\Object__ namespace.

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

As usual, you may wish to call them by their short names using the 
*Type* factory.

```php
use ActivityPub\Type;

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


{% capture doc_url %}{{ site.doc_repository_url }}/activitystreams-types.md{% endcapture %}
{% include edit-doc-link.html %}
