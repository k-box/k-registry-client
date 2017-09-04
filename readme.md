# K-Registry-Client

The K-Registry-Client is a PHP client for the K-Registry Endpoint.

## installation
the client has not been packaged with composer yet, but will be available
as a composer package.

## Usage
```php
<?php

$endpoint = "https://test.klink.asia/registry/"; // path gets added automatically

$configurator = (new HttpClientConfigurator())
    ->setEndpoint($endpoint);
$apiClient = ApiClient::configure($configurator);

// $apiClient will now be ready to query the registry

$appUri = "https://website.net/search/"; // will be the identifier of the
// remote application

$token = "jfba72hpevc8/z&"; // the authentication token supplied by the
// remote application

$permissions = array();
$permissions[] = "data-search"; // can the client search?

$apiClient->access()->check($appUri, $token, $permissions)
```

The current permissions that can be queried are:
- `data-search` - is the application allowed to search?
- `data-add` - can the application add files to the index?
- `data-delete-own` - can the application delete own indexed files?
- `data-delete-all` - can the application delete everything?

