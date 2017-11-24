# K-Link Registry client

## Installation

**TL;DR**
```bash
composer require php-http/guzzle6-adapter guzzlehttp/psr7 php-http/message oneofftech/k-link-registry-client
```

This library does not have a dependency on Guzzle, cUrl or any other library that sends HTTP requests. We use the awesome
HTTPlug to achieve the decoupling. We want you to choose what library to use for sending HTTP requests. Consult this list
of packages that support [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation)
find clients to use. For more information about virtual packages please refer to
[HTTPlug](http://docs.php-http.org/en/latest/httplug/users.html).
Example:

```bash
composer require php-http/guzzle6-adapter
```

You do also need to install a PSR-7 implementation and a factory to create PSR-7 messages (PSR-17 whenever that is
released). You could use Guzzles PSR-7 implementation and factories from php-http:

```bash
composer require guzzlehttp/psr7 php-http/message
```

Now you may install the library by running the following:

```bash
composer require oneofftech/k-link-registry-client
```

## Usage example

```php
<?php

require_once 'vendor/autoload.php';

// No trailing slash for the KRegistry endpoint
// Specify the URL without the `/api/x.y` part.
$endpoint = 'https://test.klink.asia/kregistry';

$configurator = (new HttpClientConfigurator())->setEndpoint($endpoint);
$apiClient = ApiClient::fromConfigurator($configurator);

try {
    $application = $apiClient->application()->getApplication('appSecret', 'appUrl');
    var_dump($application->getEmail());
    var_dump($application->getPermissions());

```

To check if an application has a specific permission, use the `Application::hasPermission(string)` call
on the Model returned by the `->getApplication(..)` call.

```php
<?php
    $application = $apiClient->application()->getApplication('appSecret', 'appUrl');
    var_dump($application->hasPermission('data-add'));

```
