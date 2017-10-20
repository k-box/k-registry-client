# K Registry client

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

use OneOffTech\KLinkRegistryClient\Client;

$registry_url = "https://test.klink.asia/kregistry/";

// Grabbing the access API client
$accessApi = (new Client($registry_url))->access();

// Permission check only
if ($accessApi->hasPermission($appToken, $appUrl, ["data-search"])) {
    // ... ;
}

// Verify that the application exists and grab the application details.
// empty permission array means that we want to fetch the application,
// regardless of the permissions.
$app = $accessApi->getApplication($appToken, $appUrl, []);

// now we can check on permissions of the application object:
if ($app->hasPermission("data-add")) {
    // ... ;
}

if ($app->hasPermission("data-search")) {
    // ... ;
}

// or we can get individual properties:

var_dump($app->getName());
```
