---
layout: default
permalink: fetch-peertube-outbox-activities-using-ontologies.html
title: Fetch Peertube Outbox activities using ActivityPhp's ontologies in PHP
excerpt: How to fetch Peertube Outbox activities using ActivityPhp's ontologies in PHP.
---

Using ontologies to fetch Peertube Outbox activities
====================================================

There are 2 ways to use ontologies:

- use existing ontologies
- define a new one and load it

Luckily, ActivityPhp is packaged with Peertube's ontology.

So, you just have to set `peertube` configuration.

If you want to be more permissive (in order to work with all federated
ontologies), simply use `*` parameter.


Fetch Peertube Outbox activities
--------------------------------

Below, a complete script to work with Peertube's objects. It browses all
outbox pages, collect all activities and display a list of activities
and their videos names.

```php
use ActivityPhp\Server;

/* ------------------------------------------------------------------
 | Use an instance with a PeerTube flavor
   ------------------------------------------------------------------ */

// Create a server instance and allow peertube ontology
$server = new Server([
    'ontologies' => [
        'peertube',
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

- [Extending ActivityPub Vocabulary with custom ontologies]({{ site.doc_baseurl }}/activitypub-ontologies-management.html).

- [Configuring a server instance]({{ site.doc_baseurl }}/activitypub-server-usage.html).

- [Server methods]({{ site.doc_baseurl }}/#server).


________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/server/example-fetch-peertube-outbox-activities-using-ontologies.md{% endcapture %}
{% include edit-doc-link.html %}
