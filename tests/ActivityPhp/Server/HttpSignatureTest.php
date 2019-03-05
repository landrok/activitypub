<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Server\Http\HttpSignature;
use ActivityPhp\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
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
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

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
        $request = Request::create(
            'http://localhost:8000' . $path,
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            $_SERVER,
            $payload
        );

        $request->headers->set('accept', 'application/activity+json');

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request->headers->set('Signature', 'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date",signature="' . base64_encode($signature) . '"');
        $request->headers->set('host', $host);
        $request->headers->set('date', $date);

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns true
        $this->assertEquals(
            true,
            $httpSignature->verify($request)
        );
    }

    /**
     * Check that it returns false when signature header is not 
     * specified
     */
    public function testWrongSignatureMissingSignatureHeader()
    {
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

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
        $request = Request::create(
            'http://localhost:8000' . $path,
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            $_SERVER,
            $payload
        );

        $request->headers->set('accept', 'application/activity+json');

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request->headers->set('host', $host);
        $request->headers->set('date', $date);

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
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

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
        $request = Request::create(
            'http://localhost:8000' . $path,
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            $_SERVER,
            $payload
        );

        $request->headers->set('accept', 'application/activity+json');

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request->headers->set('Signature', 'headers="(request-target) host date",signature="' . base64_encode($signature) . '"');
        $request->headers->set('host', $host);
        $request->headers->set('date', $date);

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
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

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
        $request = Request::create(
            'http://localhost:8000' . $path,
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            $_SERVER,
            $payload
        );

        $request->headers->set('accept', 'application/activity+json');

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request->headers->set('Signature', 'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date"');
        $request->headers->set('host', $host);
        $request->headers->set('date', $date);

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns false
        $this->assertEquals(
            false,
            $httpSignature->verify($request)
        );
    }

    /**
     * Check that it throws an Exception when actor does not exist
     * 
     * @expectedException \Exception
     */
    public function testWrongSignatureActorDoesNotExist()
    {
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

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
        $request = Request::create(
            'http://localhost:8000' . $path,
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            $_SERVER,
            $payload
        );

        $request->headers->set('accept', 'application/activity+json');

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request->headers->set('Signature', 'keyId="http://localhost:8001/accounts/bobb#main-key",headers="(request-target) host date",signature="' . base64_encode($signature) . '"');
        $request->headers->set('host', $host);
        $request->headers->set('date', $date);

        $httpSignature = new HttpSignature($server);
        $httpSignature->verify($request);
    }

    /**
     * Check that it returns false when signature is not verified
     */
    public function testWrongSignatureNotVerifiedSignature()
    {
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

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
        $request = Request::create(
            'http://localhost:8000' . $path,
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            $_SERVER,
            $payload
        );

        $request->headers->set('accept', 'application/activity+json');

        // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
        $request->headers->set('Signature', 'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date",signature="' . base64_encode($signature) . '"');
        $request->headers->set('host', $host);
        $request->headers->set('date', date('Y-m-d'));

        $httpSignature = new HttpSignature($server);

        // Assert verify method returns false
        $this->assertEquals(
            false,
            $httpSignature->verify($request)
        );
    }
}
