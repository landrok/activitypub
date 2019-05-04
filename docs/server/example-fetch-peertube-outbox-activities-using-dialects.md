---
layout: default
permalink: fetch-peertube-outbox-activities-using-dialects.html
title: Fetch Peertube Outbox activities using ActivityPhp's dialects in PHP
excerpt: How to fetch Peertube Outbox activities using ActivityPhp's dialects in PHP.
---

Using dialects to fetch Peertube Outbox activities
==================================================

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
use ActivityPhp\Server;

/* ------------------------------------------------------------------
 | We'll use the following definitions to fit PeerTube's dialect 
   ------------------------------------------------------------------ */
   
$peertube = [
    'Group'  => ['support'],
    'Video'  => [
        'uuid', 'category', 'language', 'views', 'sensitive', 'waitTranscoding', 
        'state', 'commentsEnabled', 'support', 'subtitleLanguage', 'likes', 
        'dislikes', 'shares', 'comments', 'licence'
    ],
    'Image'   => ['width', 'height'],
    'Link'    => ['fps', 'mimeType', 'size' ],
    'Hashtag' => ['type'],
    'Person|Group' => ['uuid', 'publicKey'],
];

```

After that, we set these definitions as peertube's dialect:

```php
// Create a server instance with a custom dialect
$server = new Server([
    'dialects' => [
        'peertube' => $peertube,
    ]
]);
```

And now we can work with Peertube's objects!

________________________________________________________________________


Fetch Peertube Outbox activities
--------------------------------

Below, a complete script to work with Peertube's objects. It browses all
outbox pages, collect all activities and display a list of activities
and their videos names.

```php
use ActivityPhp\Server;

/* ------------------------------------------------------------------
 | We'll use the following definitions to fit PeerTube's dialect 
   ------------------------------------------------------------------ */
$peertube = [
    'Group'  => ['support'],
    'Video'  => [
        'uuid', 'category', 'language', 'views', 'sensitive', 'waitTranscoding', 
        'state', 'commentsEnabled', 'support', 'subtitleLanguage', 'likes', 
        'dislikes', 'shares', 'comments', 'licence'
    ],
    'Image'   => ['width', 'height'],
    'Link'    => ['fps', 'mimeType', 'size' ],
    'Hashtag' => ['type'],
    'Person|Group' => ['uuid', 'publicKey'],
];


/* ------------------------------------------------------------------
 | Now we can use an instance with a PeerTube flavor
   ------------------------------------------------------------------ */

// Create a server instance with a custom dialect
$server = new Server([
    'dialects' => [
        'peertube' => $peertube,
    ]
]);

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

- [Extending ActivityPub Vocabulary with custom dialects]({{ site.doc_baseurl }}/activitypub-dialects-management.html).

- [Configuring a server instance]({{ site.doc_baseurl }}/activitypub-server-usage.html).

- [Server methods]({{ site.doc_baseurl }}/#server).


________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/server/example-fetch-peertube-outbox-activities-using-dialects.md{% endcapture %}
{% include edit-doc-link.html %}
