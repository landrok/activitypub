<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Server\Http\HttpSignature;
use Exception;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use phpseclib\Crypt\RSA;

/*
 * These scenarios are around verifying an HTTP signature
 * 
 * Requests are sent from localhost:8001 (distant)
 */
class HttpSignatureTest extends TestCase
{
    /**
     * Check that a given request is correctly signed
     */
    public function testValidSignature()
    {
        $httpFactory = new Psr17Factory();
        $server = new Server([
            'logger'    => [
                'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $payload = json_encode([]);

        /* ------------------------------------------------------------------
         | Prepare signature
         | ------------------------------------------------------------------ */
        $date = gmdate('D, d M Y H:i:s T', time());
        $host = 'localhost';
        $path = '/my-path?q=ok';

        $rsa = new RSA();
        $rsa->loadKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            )
        ); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

        $rsa->setHash("sha256");
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS);
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */

        $request = $httpFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER);
        $request->getBody()->write($payload);

        $request = $request->withHeader('accept', 'application/activity+json');

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request = $request
            ->withHeader(
                'Signature',
                'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date",signature="' . base64_encode($signature) . '"'
            )
            ->withHeader('Host', $host)
            ->withHeader('Date', $date);

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns true
        $this->assertEquals(
            true,
            $httpSignature->verify($request)
        );
    }

    /**
     * Check that a given request is correctly signed
     * With a optional headers not specified (fallback on date)
     */
    public function testValidSignatureWithFallbackHeaders()
    {
        $responseFactory = new Psr17Factory();
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $responseFactory);

        $payload = json_encode([]);
                
        /* ------------------------------------------------------------------
         | Prepare signature
         | ------------------------------------------------------------------ */
        $date = gmdate('D, d M Y H:i:s T', time()); 
        $host = 'localhost';
        $path = '/my-path?q=ok';

        $rsa = new RSA();
        $rsa->loadKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            )  
        ); // private key


        $plaintext = "(request-target) post $path\ndate: $date";

        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */
        $request = $responseFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER);
        $request->getBody()->write($payload);

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request = $request
            ->withAddedHeader('Accept', 'application/activity+json')
            ->withAddedHeader('Signature', 'keyId="http://localhost:8001/accounts/bob#main-key",signature="' . base64_encode($signature) . '"')
            ->withAddedHeader('Host', $host)
            ->withAddedHeader('Date', $date);

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns true
        $this->assertTrue($httpSignature->verify($request));
    }

    /**
     * Check that it returns false when signature header is not 
     * specified
     */
    public function testWrongSignatureMissingSignatureHeader()
    {
        $responseFactory = new Psr17Factory();
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $responseFactory);

        $payload = json_encode([]);
                
        /* ------------------------------------------------------------------
         | Prepare signature
         | ------------------------------------------------------------------ */
        $date = gmdate('D, d M Y H:i:s T', time()); 
        $host = 'localhost';
        $path = '/my-path?q=ok';

        $rsa = new RSA();
        $rsa->loadKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            )  
        ); // private key


        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */
        $request = $responseFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER);
        $request->getBody()->write($payload);

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request
            ->withAddedHeader('Accept', 'application/activity+json')
            ->withAddedHeader('Host', $host)
            ->withAddedHeader('Date', $date);

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns false
        $this->assertEquals(
            false,
            $httpSignature->verify($request)
        );
    }

    /**
     * Check that it returns false when keyId is not specified
     */
    public function testWrongSignatureMissingKeyId()
    {
        $httpFactory = new Psr17Factory();
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $payload = json_encode([]);
                
        /* ------------------------------------------------------------------
         | Prepare signature
         | ------------------------------------------------------------------ */
        $date = gmdate('D, d M Y H:i:s T', time()); 
        $host = 'localhost';
        $path = '/my-path?q=ok';

        $rsa = new RSA();
        $rsa->loadKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            )  
        ); // private key


        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */
        $request = $httpFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER);
        $request->getBody()->write($payload);

        $request = $request
            ->withAddedHeader('Accept', 'application/activity+json')
            ->withAddedHeader('Host', $host)
            ->withAddedHeader('Date', $date);

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request = $request->withAddedHeader(
            'Signature',
            'headers="(request-target) host date",signature="' . base64_encode($signature) . '"'
        );

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns false
        $this->assertEquals(
            false,
            $httpSignature->verify($request)
        );
    }

    /**
     * Check that it returns false when signature is not specified
     */
    public function testWrongSignatureMissingSignature()
    {
        $httpFactory = new Psr17Factory();
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $payload = json_encode([]);
                
        /* ------------------------------------------------------------------
         | Prepare signature
         | ------------------------------------------------------------------ */
        $date = gmdate('D, d M Y H:i:s T', time()); 
        $host = 'localhost';
        $path = '/my-path?q=ok';

        $rsa = new RSA();
        $rsa->loadKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            )  
        ); // private key


        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */
        $request = $httpFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER);
        $request->getBody()->write($payload);

        $request = $request
            ->withAddedHeader('Accept', 'application/activity+json')
            ->withAddedHeader('Host', $host)
            ->withAddedHeader('Date', $date);

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request = $request->withAddedHeader(
            'Signature',
            'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date"'
        );

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns false
        $this->assertEquals(
            false,
            $httpSignature->verify($request)
        );
    }

    /**
     * Check that it throws an Exception when actor does not exist
     */
    public function testWrongSignatureActorDoesNotExist()
    {
        $this->expectException(Exception::class);

        $httpFactory = new Psr17Factory();
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $payload = json_encode([]);
                
        /* ------------------------------------------------------------------
         | Prepare signature
         | ------------------------------------------------------------------ */
        $date = gmdate('D, d M Y H:i:s T', time()); 
        $host = 'localhost';
        $path = '/my-path?q=ok';

        $rsa = new RSA();
        $rsa->loadKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            )  
        ); // private key


        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */
        $request = $httpFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER);
        $request->getBody()->write($payload);

        $request = $request
            ->withAddedHeader('Accept', 'application/activity+json')
            ->withAddedHeader('Host', $host)
            ->withAddedHeader('Date', $date);

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request = $request->withAddedHeader(
            'Signature',
            'keyId="http://localhost:8001/accounts/bobb#main-key",headers="(request-target) host date",signature="' . base64_encode($signature) . '"'
        );

        $httpSignature = new HttpSignature($server);
        $httpSignature->verify($request);
    }

    /**
     * Check that it returns false when signature is not verified
     */
    public function testWrongSignatureNotVerifiedSignature()
    {
        $httpFactory = new Psr17Factory();
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ], $httpFactory);

        $payload = json_encode([]);
                
        /* ------------------------------------------------------------------
         | Prepare signature
         | ------------------------------------------------------------------ */
        $date = gmdate('D, d M Y H:i:s T', time()); 
        $host = 'localhost';
        $path = '/my-path?q=ok';

        $rsa = new RSA();
        $rsa->loadKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            )  
        ); // private key


        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */
        $request = $httpFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER);
        $request->getBody()->write($payload);

        $request = $request
            ->withAddedHeader('Accept', 'application/activity+json')
            ->withAddedHeader('Host', $host)
            ->withAddedHeader('Date', date('Y-m-d'));

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request->withAddedHeader(
            'Signature',
            'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date",signature="' . base64_encode($signature) . '"'
        );

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns false
        $this->assertEquals(
            false,
            $httpSignature->verify($request)
        );
    }
}
