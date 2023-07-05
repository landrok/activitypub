<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phpseclib3\Crypt\PublicKeyLoader;

/*
 * These scenarios are around receiving a POST on a local INBOX
 * 
 * Requests are sent from localhost:8001 (distant)
 *                   to   localhost:8000 (local)
 */
class InboxPostTest extends TestCase
{
    /**
     * Check that a given request is correctly signed
     */
    public function testValidSignature()
    {
        $server = new Server([
            'instance' => [
                'host'  => 'localhost',
                'port'  => 8000,
                'debug' => true,
                'actorPath' => '/accounts/<handle>',
            ],
            'logger'    => [
               'driver' => '\Psr\Log\NullLogger'
            ],
            'http'    => [
               'timeout' => 15
            ],
            'cache' => [
                'enabled' => false,
            ]
        ]);

        // Create a response to a message for example
        $object = json_decode(' {
                "id": "http://localhost:8001/accounts/bob/status/123",
                "type": "Note",
                "published": "'.date('Y-m-d\TH:i:s\Z').'",
                "attributedTo": "http://localhost:8001/accounts/bob",
                "inReplyTo": "http://localhost:8000/accounts/bob",
                "content": "<p>Hello world</p>",
                "to": "https://www.w3.org/ns/activitystreams#Public"
            }', true);

        $payload = json_encode(
            Type::create([
                'type' => 'Create', 
                'object' => $object
            ])->toArray()
        );
                
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
        $request->headers->set('date', $date);


        $response = $server->inbox('bob@localhost:8000')->post($request);

        // Assert response type
        $this->assertInstanceOf(Response::class, $response);

        // Assert HTTP status code
        $this->assertEquals(
            201,
            $response->getStatusCode()
        );
    }
}
