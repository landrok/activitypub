<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Server\Http\HttpSignature;
use ActivityPhp\Type;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use phpseclib3\Crypt\PublicKeyLoader;

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

        $rsa = PublicKeyLoader::loadPrivateKey(
            file_get_contents(
                dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
            ) 
        )->withHash('sha256'); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

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
     * Check that the pattern used splitting signature
     * is working as intended
     */
    public function testSplittingSignature()
    {
        $server = new Server([
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);
        
        $verifier = new HttpSignature($server);
        
        // Split a signature with headers but no algorithm
        $signature = 'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date",signature="FbVtmZhMWrfbqQpXf1v86+ie/fL8Ng4O67PePKvxChnUtV7J8N6lndQcNfXcDuKDJ4Nda6gKUQabAF2JK2qeYPNZNJ1AdAa5Lak3hQd+rAbdMJdvQpzGhAaSWK6atqOTH9v2CWdjAQbzvY0nOfGiw3ymtDSvTL0pVlIvq116uMtci0WOHeIbuBSyzM23liJmBomlm4EeB3/V1BVWY2MwaQ1cHVzxR7epP6XYts3C1KbZrdMKxhlWJFLdbLy0YGu5HRkYZepAh2q2NriSikNg8YTJ67owgQv/LqhFKnObgZU6np54fBMSpg7eAdWSIbhhg1a/WHtzFicc9cgoWMRhEg=="';
    
        $split = $verifier->splitSignature($signature);
        
        $this->assertEquals($split, [ 
            'keyId' => 'http://localhost:8001/accounts/bob#main-key',
            'algorithm' => '',
            'headers' =>  ' host date',
            'signature' => 'FbVtmZhMWrfbqQpXf1v86+ie/fL8Ng4O67PePKvxChnUtV7J8N6lndQcNfXcDuKDJ4Nda6gKUQabAF2JK2qeYPNZNJ1AdAa5Lak3hQd+rAbdMJdvQpzGhAaSWK6atqOTH9v2CWdjAQbzvY0nOfGiw3ymtDSvTL0pVlIvq116uMtci0WOHeIbuBSyzM23liJmBomlm4EeB3/V1BVWY2MwaQ1cHVzxR7epP6XYts3C1KbZrdMKxhlWJFLdbLy0YGu5HRkYZepAh2q2NriSikNg8YTJ67owgQv/LqhFKnObgZU6np54fBMSpg7eAdWSIbhhg1a/WHtzFicc9cgoWMRhEg==',
        ]);
        
        // Split a signature with headers and algorithm
        $signature = 'keyId="http://localhost:8001/accounts/bob#main-key",algorithm="rsa-sha256",headers="(request-target) host date",signature="FbVtmZhMWrfbqQpXf1v86+ie/fL8Ng4O67PePKvxChnUtV7J8N6lndQcNfXcDuKDJ4Nda6gKUQabAF2JK2qeYPNZNJ1AdAa5Lak3hQd+rAbdMJdvQpzGhAaSWK6atqOTH9v2CWdjAQbzvY0nOfGiw3ymtDSvTL0pVlIvq116uMtci0WOHeIbuBSyzM23liJmBomlm4EeB3/V1BVWY2MwaQ1cHVzxR7epP6XYts3C1KbZrdMKxhlWJFLdbLy0YGu5HRkYZepAh2q2NriSikNg8YTJ67owgQv/LqhFKnObgZU6np54fBMSpg7eAdWSIbhhg1a/WHtzFicc9cgoWMRhEg=="';
    
        $split = $verifier->splitSignature($signature);
        
        $this->assertEquals($split, [ 
            'keyId' => 'http://localhost:8001/accounts/bob#main-key',
            'algorithm' => 'rsa-sha256',
            'headers' =>  ' host date',
            'signature' => 'FbVtmZhMWrfbqQpXf1v86+ie/fL8Ng4O67PePKvxChnUtV7J8N6lndQcNfXcDuKDJ4Nda6gKUQabAF2JK2qeYPNZNJ1AdAa5Lak3hQd+rAbdMJdvQpzGhAaSWK6atqOTH9v2CWdjAQbzvY0nOfGiw3ymtDSvTL0pVlIvq116uMtci0WOHeIbuBSyzM23liJmBomlm4EeB3/V1BVWY2MwaQ1cHVzxR7epP6XYts3C1KbZrdMKxhlWJFLdbLy0YGu5HRkYZepAh2q2NriSikNg8YTJ67owgQv/LqhFKnObgZU6np54fBMSpg7eAdWSIbhhg1a/WHtzFicc9cgoWMRhEg==',
        ]);

        // Split a signature with headers (headers contains hyphens), algorithm. 
        // For informtion, the following signature is false, no problem here as
        // we're only testing split HTTP signature component. Verification is 
        // made after
        $signature = 'keyId="http://localhost:8001/accounts/bob#main-key",algorithm="rsa-sha256",headers="(request-target) host content-type digest date",signature="FbVtmZhMWrfbqQpXf1v86+ie/fL8Ng4O67PePKvxChnUtV7J8N6lndQcNfXcDuKDJ4Nda6gKUQabAF2JK2qeYPNZNJ1AdAa5Lak3hQd+rAbdMJdvQpzGhAaSWK6atqOTH9v2CWdjAQbzvY0nOfGiw3ymtDSvTL0pVlIvq116uMtci0WOHeIbuBSyzM23liJmBomlm4EeB3/V1BVWY2MwaQ1cHVzxR7epP6XYts3C1KbZrdMKxhlWJFLdbLy0YGu5HRkYZepAh2q2NriSikNg8YTJ67owgQv/LqhFKnObgZU6np54fBMSpg7eAdWSIbhhg1a/WHtzFicc9cgoWMRhEg=="';
    
        $split = $verifier->splitSignature($signature);
        
        $this->assertEquals($split, [ 
            'keyId' => 'http://localhost:8001/accounts/bob#main-key',
            'algorithm' => 'rsa-sha256',
            'headers' =>  ' host content-type digest date',
            'signature' => 'FbVtmZhMWrfbqQpXf1v86+ie/fL8Ng4O67PePKvxChnUtV7J8N6lndQcNfXcDuKDJ4Nda6gKUQabAF2JK2qeYPNZNJ1AdAa5Lak3hQd+rAbdMJdvQpzGhAaSWK6atqOTH9v2CWdjAQbzvY0nOfGiw3ymtDSvTL0pVlIvq116uMtci0WOHeIbuBSyzM23liJmBomlm4EeB3/V1BVWY2MwaQ1cHVzxR7epP6XYts3C1KbZrdMKxhlWJFLdbLy0YGu5HRkYZepAh2q2NriSikNg8YTJ67owgQv/LqhFKnObgZU6np54fBMSpg7eAdWSIbhhg1a/WHtzFicc9cgoWMRhEg==',
        ]);
    }

    /**
     * Check that a given request is correctly signed
     * With a optionnal headers not specified (fallback on date)
     */
    public function testValidSignatureWithFallbackHeaders()
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

        $rsa = PublicKeyLoader::loadPrivateKey(
                        file_get_contents(
                            dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
                        )  
                    )->withHash("sha256"); // private key

        $plaintext = "(request-target) post $path\ndate: $date";
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
        $request->headers->set('Signature', 'keyId="http://localhost:8001/accounts/bob#main-key",signature="' . base64_encode($signature) . '"');
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

        $rsa = PublicKeyLoader::loadPrivateKey(
                file_get_contents(
                    dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
                )  
            )->withHash("sha256"); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";
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

        $rsa = PublicKeyLoader::loadPrivateKey(
                file_get_contents(
                    dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
                )  
            )->withHash("sha256"); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";
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

        $rsa = PublicKeyLoader::loadPrivateKey(
                file_get_contents(
                    dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
                )  
            )->withHash("sha256"); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";
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
     */
    public function testWrongSignatureActorDoesNotExist()
    {
        $this->expectException(Exception::class);

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

        $rsa = PublicKeyLoader::loadPrivateKey(
                file_get_contents(
                    dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
                )  
            )->withHash("sha256"); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";
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

        $rsa = PublicKeyLoader::loadPrivateKey(
                file_get_contents(
                    dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem'
                )  
            )->withHash("sha256"); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";
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
