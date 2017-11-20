# Sanity service provider for Silex
A service provider for [Silex](https://silex.symfony.com/) that can be used to communicate with the [Sanity API](https://www.sanity.io/) using the [PHP library](https://github.com/sanity-io/sanity-php).

## Requirements
The service provider can be used with Silex >= 2.0.

## Installation
You can install the library via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require sanity/silex-service-provider
```

To load the service provider simply register it in the Silex application:

```php
<?php
$app = new Silex\Application();
$app->register(new Sanity\Silex\ServiceProvider(), [
    'sanity.client.options' => [
        'projectId' => '<project id>', // required
        'dataset' => '<dataset>',      // required
        'useCdn' => true,
    ],
]);
```

## Usage
The service provider exposes the API client through a service called `sanity.client` that can be fetched from the application instance:

```php
$apiClient = $app['sanity.client'];
```

Learn more about [how to use the PHP library for the Sanity API](https://github.com/sanity-io/sanity-php).

## Contributing
`sanity/silex-service-provider` follows the [PSR-2 Coding Style Guide](http://www.php-fig.org/psr/psr-2/). Contributions are welcome, but must conform to this standard.

## License
MIT-licensed, see LICENSE.
