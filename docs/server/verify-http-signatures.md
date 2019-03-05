---
layout: default
permalink: activitypub-server-verify-http-signatures.html
title: Verify an HTTP signature with ActivityPub server in PHP
excerpt: How to verify an HTTP signature with ActivityPub server in PHP.
---

ActivityPub Server - Verify HTTP signatures
===========================================

ActivityPhp server automatically verifies HTTP signatures when handling
a POST to an inbox (incoming messages).

For this, it provides a dedicated tool which may be used out-of-the box.

Usage
-----

```php
use ActivityPhp\Server;
use ActivityPhp\Server\Http\HttpSignature;

// Create a server instance
$server = new Server();

// Create an HttpSignature instance
$httpSignature = new HttpSignature($server);

var_dump(
    // Verify signature
    $httpSignature->verify($request)
);

// Returns bool(true) if signature has been verified

```

`HttpSignature` receives a server instance in its constructor method. 
Indeed, it is useful to make some checks about actor who has sent this
incoming request.

________________________________________________________________________


HttpSignature::verify()
-----------------------

It returns a boolean. `true` if the signature has been verified, `false`
otherwise.

This method accepts one parameter. It must be a 
`Symfony\Component\HttpFoundation\Request` instance.

This request MUST contain a Signature header that specifies [keyId,
headers, signature].

Read more on the [HTTP signature components](https://tools.ietf.org/html/draft-cavage-http-signatures-10#section-2.1).

The verification process follows the following steps:

- Parses HTTP signature if it is in the headers
- Checks that actor is really declared on the distant instance
- Gets actor's public key
- Verifies HTTP signature

________________________________________________________________________


{% capture doc_url %}{{ site.doc_repository_url }}/server/verify-http-signatures.md{% endcapture %}
{% include edit-doc-link.html %}
