.. title:: ActivityPhp manual
.. meta::
   :description: ActivityPhp is an implementation of the ActivityPub and ActivityStreams layers in PHP.
   :keywords: ActivityPhp ActivityPub ActivityStreams Federation Server Client
   :author: Landrok


=========================
ActivityPhp Documentation
=========================

|latest-stable| |build-status| |license|

**ActivityPhp** is an implementation of ActivityPub layers in PHP.

It provides two layers:

- A __client to server protocol__, or "Social API"
    This protocol permits a client to act on behalf of a user.
- A [__server to server protocol__]({{ site.doc_baseurl }}/#server), or "Federation Protocol"
    This protocol is used to distribute activities between actors on
    different servers, tying them into the same social graph.

As the two layers are implemented, it aims to be an ActivityPub
conformant Federated Server.

All [normalized types]({{ site.doc_baseurl }}/activitystreams-types.html)
are implemented too. If you need to create a new one, just extend
existing implementations.

.. code-block:: php

    use ActivityPhp\Server;
    use ActivityPhp\Type;

    $note = Type::create('Note');

    $note = Type::create('Note', [
        'content' => 'A content for my note'
    ]);

    $array = [
        'type'    => 'Note',
        'content' => 'A content for my note'
    ];

    $note = Type::create($array);

    $server = new Server();



.. |build-status| image:: https://api.travis-ci.org/landrok/activitypub.svg?branch=master
    :alt: Build status
    :target: https://travis-ci.org/landrok/activitypub

.. |latest-stable| image:: https://poser.pugx.org/landrok/activitypub/version.svg
    :alt: Latest
    :target: https://github.com/landrok/activitypub/releases

.. |license| image:: https://poser.pugx.org/landrok/activitypub/license.svg
    :alt: License
    :target: https://packagist.org/packages/landrok/activitypub
