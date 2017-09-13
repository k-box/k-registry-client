# K Registry client

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
$app = $accessApi->getApplication($appToken, $appUrl, ["data-delete-own"]);

var_dump($app->getName());
// ...
```
