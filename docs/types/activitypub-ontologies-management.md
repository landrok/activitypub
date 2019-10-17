---
layout: default
permalink: activitypub-ontologies-management.html
title: Extending ActivityPub Vocabulary with custom ontology
excerpt: How to implement a custom ontology on top of the ActivityPub Vocabulary in PHP.
---

ActivityPub ontologies
====================

In a previous example, we've seen how to extend ActivityPub types with
[dialects]({{ site.doc_baseurl }}/activitypub-dialects-management.html). 

There is another method to dynamically extend defined types or to create
new ones.

This method is based on `ActivityPhp\Type\Ontology` tool.

In this manual, we'll only see basics of its API. For more practical 
aspects, there is a [dedicated example in server's part]({{ site.doc_baseurl }}/fetch-peertube-outbox-activities-using-ontologies.html).

________________________________________________________________________


- [Create an ontology]({{ site.doc_baseurl }}/activitypub-ontologies-management.html#create-your-ontology)
- [Add and load your ontology]({{ site.doc_baseurl }}/activitypub-ontologies-management.html#add-and-load-your-ontology)
- [Only add your ontology]({{ site.doc_baseurl }}/activitypub-ontologies-management.html#only-add-your-ontology)
- [Load a particular ontology]({{ site.doc_baseurl }}/activitypub-ontologies-management.html#load-a-particular-ontology)
- [Unload ontologies]({{ site.doc_baseurl }}/activitypub-ontologies-management.html#unload-ontologies)
- [Contribute with your ontologies]({{ site.doc_baseurl }}/activitypub-ontologies-management.html#contribute-with-your-ontologies)

________________________________________________________________________

Create your ontology
-------------------

Before creating your own ontology, keep in mind that ActivityPub already
implements a lot of stuff. Check that they do not fit your needs before
reinventing the wheel :)

In order to create a new ontology, you have to extend 
`ActivityPhp\Type\OntologyBase` class.

If you want to see a complete and working ontology, let's see 
[Peertube's one]({{ site.repository_url }}/tree/master/src/ActivityPhp/Type/Ontology/Peertube.php).

Let's define our simple ontology.

```php
use ActivityPhp\Type\OntologyBase;

abstract class SimpleOntology extends OntologyBase
{
    /**
     * A definition of dialect to overload Activity Streams
     * vocabulary.
     * 
     * @var array
     */
    protected static $definitions = [
        'Group'  => ['myNewProperty'],
    ];
}

```

When a type (Group in previous example) does not exist, it is 
transparently created.

________________________________________________________________________

Add and load your ontology
--------------------------

After defining it, you have to load it.

```php
use ActivityPhp\Type;
use ActivityPhp\Type\Ontology;


// Add and load this ontology
Ontology::add('simple-ontology', SimpleOntology::class);

// Now you can use this property
$group = Type::create('Group');
$group->myNewProperty = true;

```

Of course, you may accomplish that in a server context

```php
use ActivityPhp\Server;

// Create a server instance and allow our simple ontology
$server = new Server([
    'ontologies' => [
        'simple-ontology' => SimpleOntology::class,
    ]
]);

```

________________________________________________________________________

Only add your ontology
----------------------

It may be useful to add a set of dialects without allowing them.

It's the role of the 3rd parameter. Ontology definitions are stacked,
ready to be loaded but not usable.

```php
use ActivityPhp\Type\Ontology;

// Add 2 definitions but don't load these dialects
Ontology::add('simple-ontology', SimpleOntology::class, false);

```

This way, your ontologies are configurable as plugins among contexts.

To complete this usage, you have a `Ontology::load()` method

________________________________________________________________________

Load a particular ontology
--------------------------

Based on previous example, you may now load specifically a particular 
ontology.

```php

// Load only simple ontology
Ontology::load('mydialect1');

// Load all available ontologies
Ontology::load('*');

```
________________________________________________________________________

Unload ontologies
-----------------

Sometimes, you need to keep only ActivityPub standard vocabulary.

You can unload ontologies.

```php

// Unload only simple ontolgy
Ontology::unload('simple-ontology');

// Unload all available ontologies
Ontology::unload('*');

```

________________________________________________________________________


Contribute with your ontologies
-------------------------------

This feature is made to ease implementation of major ontologies 
(Mastodon, Peertube, Pixelfed, etc...).

If you successfully implemented a new one, feel free to contribute with 
a [pull request]({{ site.repository_url }})!



{% capture doc_url %}{{ site.doc_repository_url }}/types/activitypub-ontologies-management.md{% endcapture %}
{% include edit-doc-link.html %}
