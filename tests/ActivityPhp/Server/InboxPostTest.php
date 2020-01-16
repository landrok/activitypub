<?php

namespace ActivityPhpTest\Server;

use ActivityPhp\Server;
use ActivityPhp\Type;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use phpseclib\Crypt\RSA;

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
        $httpFactory = new Psr17Factory();
        $client = new Server\Http\GuzzleActivityPubClient();
        $server = new Server($httpFactory, $client, [
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

        $rsa = new RSA();
        $rsa->loadKey(file_get_contents(dirname(__DIR__, 2) . '/WebServer/distant/keys/private.pem')); // private key

        $plaintext = "(request-target) post $path\nhost: $host\ndate: $date";

        $rsa->setHash("sha256"); 
        $rsa->setSignatureMode(RSA::SIGNATURE_PSS); 
        $signature = $rsa->sign($plaintext);

        /* ------------------------------------------------------------------
         | Prepare request
         | ------------------------------------------------------------------ */
        $request = $httpFactory->createServerRequest('POST', 'http://localhost:8000' . $path, $_SERVER)
            ->withHeader('Accept', 'application/activity+json')
            // Signature: keyId="<URL>",headers="(request-target) host date",signature="<SIG>"
            ->withHeader(
                'Signature',
                'keyId="http://localhost:8001/accounts/bob#main-key",headers="(request-target) host date",signature="' . base64_encode($signature) . '"'
            )
            ->withHeader('Host', $host)
            ->withHeader('Date', $date);

        $request->getBody()->write($payload);

        $response = $server->inbox('bob@localhost:8000')->post($request);

        // Assert response type
        $this->assertInstanceOf(ResponseInterface::class, $response);

        // Assert HTTP status code
        $this->assertEquals(201, $response->getStatusCode());
    }
}
