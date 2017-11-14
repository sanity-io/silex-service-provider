<?php
namespace Sanity;

use PHPUnit_Framework_TestCase;
use Silex\Application;

/**
 * @coversDefaultClass Sanity\SanityServiceProvider
 */
class SanityServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var string
     */
    private $projectId = 'zp7mbokg';

    /**
     * @var string
     */
    private $dataset = 'production';

    /**
     * Set up the application
     */
    public function setUp()
    {
        $this->app = new Application();
        $this->app->register(new SanityServiceProvider(), [
            'sanity.client.options' => [
                'projectId' => $this->projectId,
                'dataset' => $this->dataset,
            ],
        ]);
    }

    /**
     * @covers ::register
     */
    public function testServiceIsShared()
    {
        $sanity1 = $this->app['sanity'];

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

        $sanity2 = $this->app['sanity'];

        $this->assertSame($sanity1, $sanity2, 'Expected same instance of the API client');
    }

    /**
     * @covers ::register
     */
    public function testClientMakesRequestsAgainstApi()
    {
        $this->assertArraySubset([
            'projectId' => $this->projectId,
            'dataset' => $this->dataset,
        ], $this->app['sanity']->config(), 'Client configuration not properly set');
    }
}
