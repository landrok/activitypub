---
layout: default
permalink: activitypub-server-verify-http-signatures.html
title: Verify an HTTP signature with ActivityPub server in PHP
excerpt: How to verify an HTTP signature with ActivityPub server in PHP.
---

ActivityPub Server - Verifying HTTP signatures
==============================================

ActivityPhp server automatically verifies HTTP signatures when handling
a POST to an inbox (incoming messages).

For an out-of-the-box usage, a dedicated tool is provided, it's called `HttpSignature`.

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
headers, signature]. *headers* key is optional.

Read more about
[HTTP signature components](https://tools.ietf.org/html/draft-cavage-http-signatures-10#section-2.1).

The verification process follows the following steps:

- Parse HTTP signature if it is in the headers
- Check that actor is really declared on the distant instance
- Get actor's public key
- Verify HTTP signature

________________________________________________________________________

ActivityPub conformance
-----------------------

As ActivityPub protocol does not specify an official mechanism for 
[signature verification](https://www.w3.org/TR/activitypub/#authorization) 
(algorithm, headers), this implementation tries to make use of 
[good practices recommended by the Social Community Group](https://www.w3.org/wiki/SocialCG/ActivityPub/Authentication_Authorization#Signing_requests_using_HTTP_Signatures) 
and to be compliant with empirical implementations (Mastodon, Peertube 
at least).

________________________________________________________________________

{% capture doc_url %}{{ site.doc_repository_url }}/server/verify-http-signatures.md{% endcapture %}
{% include edit-doc-link.html %}
