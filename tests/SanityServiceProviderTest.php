<?php
namespace Sanity;

use PHPUnit_Framework_TestCase;
use Pimple\Container;
use Silex\Application;
use GuzzleHttp\Middleware;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * @coversDefaultClass Sanity\SanityServiceProvider
 */
class SanityServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::register
     */
    public function testServiceIsShared()
    {
        $app = new Application();
        $app->register(new SanityServiceProvider(), [
            'sanity.client.options' => [
                'projectId' => 'zp7mbokg',
                'dataset' => 'production',
            ],
        ]);

        $sanity1 = $app['sanity'];

        $this->assertInstanceOf(
            Client::class,
            $sanity1,
            sprintf(
                'Expected "sanity" service to be an instance of "%s", got %s: "%s"',
                Client::class,
                is_object($sanity1) ? 'object' : 'type',
                is_object($sanity1) ? get_class($sanity1) : gettype($sanity1)
            )
        );

        $sanity2 = $app['sanity'];

        $this->assertSame($sanity1, $sanity2, 'Expected same instance of the API client');
    }

    /**
     * @covers ::register
     */
    public function testClientMakesRequestsAgainstApi()
    {
        $history = [];
        $mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::history($history));

        $app = new Application();
        $app->register(new SanityServiceProvider(), [
            'sanity.client.options' => [
                'projectId' => $projectId = 'zp7mbokg',
                'dataset' => $dataset = 'production',
                'handler' => $handlerStack,
            ],
        ]);

        // Add a mocked API response
        $mockHandler->append(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'documents' => [
                [
                    'some' => 'value',
                ],
            ],
        ])));

        // Generate a request
        $response = $app['sanity']->getDocument('id');

        $this->assertSame(
            $expected = sprintf('https://%s.api.sanity.io/v1/data/doc/%s/id', $projectId, $dataset),
            $actual = (string) $history[0]['request']->getUri(),
            sprintf(
                'Request URI did not include the correct segments. Expected "%s", got: "%s"',
                $expected,
                $actual
            )
        );
    }
}
