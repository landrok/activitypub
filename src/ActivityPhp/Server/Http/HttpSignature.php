<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Http;

use ActivityPhp\Server;
use ActivityPhp\Type\Util;
use Symfony\Component\HttpFoundation\Request;
use phpseclib\Crypt\RSA;

/**
 * HTTP signatures tool
 */ 
class HttpSignature
{
    const SIGNATURE_PATTERN = '/^
        keyId="(?P<keyId>
            (https?:\/\/[\w\-\.]+[\w]+)
            (:[\d]+)?
            ([\w\-\.#\/@]+)
        )",
        (headers="\(request-target\) (?P<headers>[\w\s]+)",)?
        signature="(?P<signature>[\w+\/]+==)"
    /x';

    /**
     * @var \ActivityPhp\Server
     */
    protected $server;

    /**
     * Inject a server instance
     * 
     * @param \ActivityPhp\Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Verify an incoming message based upon its HTTP signature
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return bool True if signature has been verified. Otherwise false 
     */
    public function verify(Request $request)
    {
        // Read the Signature header,
        $signature = $request->headers->get('signature');

        if (!$signature) {
            $this->server->logger()->info(
                'Signature header not found',
                [$request->headers->all()]
            );
            return false;
        }

        // Split it into its parts (keyId, headers and signature)
        $parts = $this->splitSignature($signature);
        if (!$parts) {
            return false;
        }

        extract($parts);

        $this->server->logger()->debug('Signature', [$signature]);

        // Build a server-oriented actor
        // Fetch the public key linked from keyId
        $actor = $this->server->actor($keyId);

        $publicKeyPem = $actor->getPublicKeyPem();

        $this->server->logger()->debug('publicKeyPem', [$publicKeyPem]);

        // Create a comparison string from the plaintext headers we got 
        // in the same order as was given in the signature header, 
        $data = $this->getPlainText(
            explode(' ', trim($headers)), 
            $request
        );

        // Verify that string using the public key and the original 
        // signature.
        $rsa = new RSA(); 
        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $rsa->loadKey($publicKeyPem);

        return $rsa->verify($data, base64_decode($signature, true)); 
    }

    /**
     * Split HTTP signature into its parts (keyId, headers and signature)
     * 
     * @param s tring $signature
     * @return bool|array
     */
    private function splitSignature(string $signature)
    {
        if (!preg_match(self::SIGNATURE_PATTERN, $signature, $matches)) {
            $this->server->logger()->info(
                'Signature pattern failed',
                [$signature]
            );

            return false;
        }

        // Headers are optional
        if (!isset($matches['headers']) || $matches['headers'] == '') {
            $matches['headers'] = 'date';
        }

        return $matches;        
    }

    /**
     * Get plain text that has been originally signed
     * 
     * @param  array $headers HTTP header keys
     * @param  \Symfony\Component\HttpFoundation\Request $request 
     * @return string
     */
    private function getPlainText(array $headers, Request $request)
    {
        $strings = [];
        $strings[] = sprintf(
            '(request-target) %s %s%s',
            strtolower($request->getMethod()),
            $request->getPathInfo(),
            $request->getQueryString() 
                ? '?' . $request->getQueryString() : ''
        );

        foreach ($headers as $key) {
            if ($request->headers->has($key)) {
                $strings[] = "$key: " . $request->headers->get($key);
            }
        }

        return implode("\n", $strings);   
    }
}
