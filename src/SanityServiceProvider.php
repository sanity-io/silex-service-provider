<?php
namespace Sanity;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Silex\Application;

class SanityServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the API client as a service in the container
     *
     * @param Container $app
     * @return void
     */
    public function register(Container $app)
    {
        $app['sanity.client.options'] = [
            'projectId' => null,
            'dataset' => null,
        ];

        $app['sanity'] = function (Application $app) {
            return new Client($app['sanity.client.options']);
        };
    }
}
