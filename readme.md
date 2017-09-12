# K Registry client

```php
<?php

use OneOffTech\KLinkRegistryClient\Client;

$registry_url = "https://test.klink.asia/kregistry/";

$client = new Client($registry_url);

// $api will contain the access API
$api = $client->access();

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
