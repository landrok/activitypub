---
layout: default
permalink: activitypub-dialects-management.html
title: Extends ActivityPub Vocabulary with a custom dialects
excerpt: How to implement a custom dialect on top of the ActivityPub Vocabulary in PHP.
---

ActivityPub dialects
====================

In a previous example, we've seen how to extend ActivityPub types with
types customization, extending PHP class. 

There is another method to dynamically extend defined types or to define
new ones.

This method is based on `ActivityPhp\Type\Dialect` tool.

In this manual, we'll only see basics of its API. For more practical 
aspects, there is a dedicated example in server's part.

________________________________________________________________________


- [Create a dialect]({{ site.doc_baseurl }}/activitypub-dialects-management.html#create-your-dialect)
- [Add and load your dialect]({{ site.doc_baseurl }}/activitypub-dialects-management.html#add-and-load-your-dialect)
- [Only add your dialect]({{ site.doc_baseurl }}/activitypub-dialects-management.html#only-add-your-dialect)
- [Load a particular dialect]({{ site.doc_baseurl }}/activitypub-dialects-management.html#load-a-particular-dialect)
- [Unload dialects]({{ site.doc_baseurl }}/activitypub-dialects-management.html#unload-dialects)

________________________________________________________________________

Create your dialect
-------------------

In order to create a new dialect, you have to extend 
[ActivityPub types](activitystreams-types.html).

Let's start with adding 2 properties to the Person type.

```php
$dialect = [
    // Add fields to one type
    'Person' => ['featured', 'uuid'],
];
```

Sometimes, you need to attach several properties to several types;

```php
$dialect = [
    // Add fields to several types with | separator
    'Person|Application' => [
        'featured', 'manuallyApprovesFollowers'
    ],
];
```

When a type does not exist, it is transparently created.

```php
$dialect = [
    // Add fields to a new type
    'MyNewType' => [
        'propertyOne', 'propertyTwo'
    ],
];
```

________________________________________________________________________

Add and load your dialect
-------------------------

After defining it, you have to load it.

```php
use ActivityPhp\Type;
use ActivityPhp\Type\Dialect;

// Dialect definition
$dialect = [
    // Add fields to one type
    'Person' => ['featured', 'uuid'],
];

// Add and load this dialect
Dialect::add('mydialect', $dialect);

// Now you can use this property
$person = Type::create('Person');
$person->featured = true;

```

Of course, you may accomplish that in one step:

```php
use ActivityPhp\Type\Dialect;

// Define, add and load this dialect
Dialect::add('mydialect', [
    // Add fields to one type
    'Person' => ['featured', 'uuid'],
]);

```

________________________________________________________________________

Only add your dialect
---------------------

It may be useful to add a set of dialects without allowing them.

It's the role of the 3rd parameter. Dialects definitions are stacked,
ready to be loaded but not usable.

```php
use ActivityPhp\Type\Dialect;

// Dialect definition
$dialect = [
    // Add fields to one type
    'Person' => ['featured', 'uuid'],
];

// Add 2 definitions but not load these dialects
Dialect::add('mydialect1', $dialect, false);
Dialect::add('mydialect2', $dialect, false);

```

This way, your dialects are configurable as plugins among contexts.

To complete this usage, you have a `Dialect::load()` method

________________________________________________________________________

Load a particular dialect
-------------------------

Based on previous example, you may now load specifically a particular 
dialect.

```php

// Load only mydialect1
Dialect::load('mydialect1');

// Load all available dialects
Dialect::load('*');

```
________________________________________________________________________

Unload dialects
---------------

Sometimes, you need to keep only ActivityPub standard vocabulary.

You can unload dialects.

```php

// Unload only mydialect1
Dialect::unload('mydialect1');

// Unload all available dialects
Dialect::unload('*');

```

________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/types/activitypub-dialects-management.md{% endcapture %}
{% include edit-doc-link.html %}
