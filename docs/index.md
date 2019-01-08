---
layout: default
---

[![Build Status](https://travis-ci.org/landrok/activitypub.svg?branch=master)](https://travis-ci.org/landrok/activitypub)
[![Maintainability](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/maintainability)](https://codeclimate.com/github/landrok/activitypub/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/410c804f4cd03cc39b60/test_coverage)](https://codeclimate.com/github/landrok/activitypub/test_coverage)

ActivityPub is an implementation of ActivityPub layers in PHP.

It provides two layers:

- A __client to server protocol__, or "Social API"
    This protocol permits a client to act on behalf of a user.
- A __server to server protocol__, or "Federation Protocol"
    This protocol is used to distribute activities between actors on different servers, tying them into the same social graph. 

As the two layers are implemented, it aims to be an ActivityPub conformant Federated Server

All normalized types are implemented too. If you need to create a new
one, just extend existing types.

------------------------------------------------------------------------

{% capture doc_url %}{{ site.github_doc_repository_url }}/index.md{% endcapture %}
{% include edit-doc-link.html %}
