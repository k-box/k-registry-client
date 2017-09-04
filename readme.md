# K Registry client

```php
<?php

$client = new KRegistryClient();

$endpoint = "https://test.klink.asia/kregistry/";
$client->setEndpoint($endpoint);

$application = client->application("token");

if ($application->hasPermission("data-search")) {
    // ...
}

if ($application->hasPermission("data-add")) {
    // ...
}
```
