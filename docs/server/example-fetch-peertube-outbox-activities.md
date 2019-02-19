---
layout: default
permalink: fetch-peertube-outbox-activities.html
title: Fetch Peertube Outbox activities in PHP
excerpt: How to fetch Peertube Outbox activities in PHP.
---

Fetch Peertube Outbox activities
================================

For some usages, you may use an instance without having to configure 
many items.

For instance, if you want to fetch some public activities, you don't 
need to have a fully-configured server instance. Indeed, you don't need
any configuration at all.


Fetch an Outbox
---------------

```php
use ActivityPhp\Server;

// Create a server instance
$server = new Server();

$handle = 'nickname@example.org';

// Get an actor's outbox as an OrderedCollection
$outbox = $server->outbox($handle);

// Prepare a stack
$pages = [];

// Browse first page
$page = $outbox->getPage($outbox->get()->first);

// Browse all pages and get public actvities
$pages[] = $page;
while ($page->next !== null) {
    $page = $outbox->getPage($page->next);
    $pages[] = $page;
}

```

And that's it, you have an array containing all pages.

Therefore, if you test this implementation on a real world 
ActivityPub-powered application like Peertube, you would have something 
like:

```sh
Exception: Property "uuid" is not defined. Type="Video", Class="\ActivityPhp\Type\Extended\Object\Video"
```

Indeed, Peertube (and others like Mastodon) extends the ActivityPub 
object model to fit their needs.

So, in order to fetch Peertube's activities, we need to extend our base 
models with Peertube dialect.

________________________________________________________________________


Define Peertube's dialect
-------------------------

We will only *partially* implement peertube's model.

After a step-by-step exceptions analysis, we've collected all attributes
that are specific.


```php
use ActivityPhp\Type;
use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Core\Link as BaseLink;
use ActivityPhp\Type\Extended\Actor\Person as BasePerson;
use ActivityPhp\Type\Extended\Object\Image as BaseImage;
use ActivityPhp\Type\Extended\Object\Video as BaseVideo;

// User
class Person extends BasePerson
{
    protected $uuid;            // Peertube specific
    protected $publicKey;       // Peertube specific
}

// Channel
class Group extends Person
{
    protected $support;         // Peertube specific    
}

class Video extends BaseVideo
{
    protected $uuid;            // Peertube specific
    protected $category;        // Peertube specific
    protected $language;        // Peertube specific
    protected $views;           // Peertube specific
    protected $sensitive;       // Peertube specific
    protected $waitTranscoding; // Peertube specific
    protected $state;           // Peertube specific
    protected $commentsEnabled; // Peertube specific
    protected $support;         // Peertube specific
    protected $subtitleLanguage;// Peertube specific
    protected $likes;           // Peertube specific
    protected $dislikes;        // Peertube specific
    protected $shares;          // Peertube specific
    protected $comments;        // Peertube specific
    protected $licence;         // Peertube specific
}

class Image extends BaseImage
{
    protected $width;           // Peertube specific
    protected $height;          // Peertube specific
}

class Link extends BaseLink
{
    protected $fps;             // Peertube specific
    protected $mimeType;        // Peertube specific
    protected $size;            // Peertube specific
}

// Create a new Type
class HashTag extends ObjectType
{
    protected $type = 'HashTag';// Peertube specific
}
```

If you need to implement a specific dialect, just have a look at our 
[ActivityStreams types]({{ site.doc_baseurl }}/activitystreams-types.html).
Then, you may see which properties are already defined:

```php
use ActivityPhp\Type;

print_r(
    Type::create('Video')->getProperties()
);
```

This will output all normalized ActivityPub's properties for a type.

```sh
Array
(
    [0] => type
    [1] => id
    [2] => attachment
    [3] => attributedTo
    [4] => audience
    [5] => content
    [6] => context
    [7] => contentMap
    [8] => name
    [9] => nameMap
    [10] => endTime
    [11] => generator
    [12] => icon
    [13] => image
    [14] => inReplyTo
    [15] => location
    [16] => preview
    [17] => published
    [18] => replies
    [19] => startTime
    [20] => summary
    [21] => summaryMap
    [22] => tag
    [23] => updated
    [24] => url
    [25] => to
    [26] => bto
    [27] => cc
    [28] => bcc
    [29] => mediaType
    [30] => duration
    [31] => source
)

```

After that, we push all new definitions into our pool:

```php
// Add custom definitions to override defaults or introduce new ones
Type::add('Group',   Group::class);
Type::add('HashTag', HashTag::class); # new one
Type::add('Image',   Image::class);
Type::add('Link',    Link::class);
Type::add('Person',  Person::class);
Type::add('Video',   Video::class);
```

And now we can work with Peertube's objects!

________________________________________________________________________


Fetch Peertube Outbox activities
--------------------------------

Below, a complete script to work with Peertube's objects. It browses all
outbox pages, collect all activities and display a list of activities
and their videos names.

```php
use ActivityPhp\Type;
use ActivityPhp\Server;

/* ------------------------------------------------------------------
 | We'll use the following definitions to fit PeerTube's dialect 
   ------------------------------------------------------------------ */
use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Core\Link as BaseLink;
use ActivityPhp\Type\Extended\Actor\Person as BasePerson;
use ActivityPhp\Type\Extended\Object\Image as BaseImage;
use ActivityPhp\Type\Extended\Object\Video as BaseVideo;

// User
class Person extends BasePerson
{
    protected $uuid;            // Peertube specific
    protected $publicKey;       // Peertube specific
}

// Channel
class Group extends Person
{
    protected $support;         // Peertube specific    
}

class Video extends BaseVideo
{
    protected $uuid;            // Peertube specific
    protected $category;        // Peertube specific
    protected $language;        // Peertube specific
    protected $views;           // Peertube specific
    protected $sensitive;       // Peertube specific
    protected $waitTranscoding; // Peertube specific
    protected $state;           // Peertube specific
    protected $commentsEnabled; // Peertube specific
    protected $support;         // Peertube specific
    protected $subtitleLanguage;// Peertube specific
    protected $likes;           // Peertube specific
    protected $dislikes;        // Peertube specific
    protected $shares;          // Peertube specific
    protected $comments;        // Peertube specific
    protected $licence;         // Peertube specific
}

class Image extends BaseImage
{
    protected $width;           // Peertube specific
    protected $height;          // Peertube specific
}

class Link extends BaseLink
{
    protected $fps;             // Peertube specific
    protected $mimeType;        // Peertube specific
    protected $size;            // Peertube specific
}

// Create a new Type
class HashTag extends ObjectType
{
    protected $type = 'HashTag';// Peertube specific
}

// Add custom definitions to override defaults or introduce new ones
Type::add('Group',   Group::class);
Type::add('HashTag', HashTag::class); # new one
Type::add('Image',   Image::class);
Type::add('Link',    Link::class);
Type::add('Person',  Person::class);
Type::add('Video',   Video::class);


/* ------------------------------------------------------------------
 | Now we can use an instance with a PeerTube flavor
   ------------------------------------------------------------------ */

// Create a server instance
$server = new Server();

$handle = 'nickname@example.org';

// Get an actor's outbox as an OrderedCollection
$outbox = $server->outbox($handle);

// Prepare a stack
$pages = [];

// Browse first page
$page = $outbox->getPage($outbox->get()->first);

// Browse all pages and get public actvities
$pages[] = $page;
while ($page->next !== null) {
    $page = $outbox->getPage($page->next);
    $pages[] = $page;
}

// Now we can work with pages
foreach ($pages as $page) {
    foreach ($page->orderedItems as $item) {
        echo sprintf(
            "Type=%s, Name=%s\n",
            $item->type,            // Activity type
            $item->object->name     // Video name
        );
    }
}
```

________________________________________________________________________

Read more
---------


- [Configuring a server instance]({{ site.doc_baseurl }}/activitypub-server-usage.html).

- [How a server instance is working]({{ site.doc_baseurl }}/#server).


________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/server/example-fetch-peertube-outbox-activities.md{% endcapture %}
{% include edit-doc-link.html %}
