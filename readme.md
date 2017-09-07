# K Registry client

```php
<?php

$client = new OneOffTech\KLinkRegistryClient\RegistryClient();

$endpoint = "https://test.klink.asia/kregistry/";
$client->setEndpoint($endpoint);

// $api will contain the application API
$api = $client->application();

// quick permission check
if ($api->hasPermission($appToken, $appUrl, ["data-search"])) {
    // ... ;
}

if ($api->hasPermission($appToken, $appUrl, ["data-add"])) {
    // ... ;
}

// grab as many information about the authorized app as possible.
// the permissions are still required by the backend.
$app = $api->getApplication($appToken, $appUrl, ["data-delete-own"]);

echo $app->getName();
// ...

```
