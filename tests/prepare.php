<?php

/**
 * Initialize the integration test environment
 * with a K-Link and an application that
 * can publish on it.
 */
require __DIR__.'/../vendor/autoload.php';

use Http\Client\Exception\RequestException;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;

$messageFactory = MessageFactoryDiscovery::find();
$httpClient = HttpClientDiscovery::find();

$auth = [
    'email' => getenv('REGISTRY_TEST_USER') ? getenv('REGISTRY_TEST_USER') : 'admin@registry.local',
    'password' => getenv('REGISTRY_TEST_PASSWORD') ? getenv('REGISTRY_TEST_PASSWORD') : '****',
];

const AUTH_ROUTE = 'http://127.0.0.1:8080/api/2.0/auth/session';
const APPLICATIONS_ROUTE = 'http://127.0.0.1:8080/api/2.0/applications';
const KLINKS_ROUTE = 'http://127.0.0.1:8080/api/2.0/klinks';

function initializeIntegrationTestsEnvironment($token, $httpClient, $messageFactory)
{
    if (null === $token) {
        fwrite(STDERR, 'Failed to authenticate with the Registry.'.PHP_EOL);
        exit(1);
    }

    fwrite(STDOUT, 'Creating K-Link and Application on Registry'.PHP_EOL);

    // create a K-Link

    $klink = [
        'manager_id' => 1,
        'name' => 'Test K-Link',
        'website' => 'http://localhost',
        'description' => '',
        'active' => true,
    ];

    $klink_request = $messageFactory->createRequest('POST', KLINKS_ROUTE, ['Authorization' => "Bearer $token"], json_encode($klink));

    // grab the K-Link identifier
    $klink_response = $httpClient->sendRequest($klink_request);

    $klink_response_body = json_decode((string) $klink_response->getBody(), true);

    if (!$klink_response_body || !isset($klink_response_body['id'])) {
        fwrite(STDERR, 'Failed to initialize K-Link.'.PHP_EOL);
        exit(1);
    }

    $klink_id = $klink_response_body['id'];

    // create a testing application that can connect to the K-Link

    $application = [
        'owner_id' => 1,
        'name' => 'Test Application',
        'app_domain' => 'http://localhost',
        'active' => true,
        'permissions' => ['data-search', 'data-view'],
        'klinks' => [$klink_id],
    ];

    $application_request = $messageFactory->createRequest('POST', APPLICATIONS_ROUTE, ['Authorization' => "Bearer $token"], json_encode($application));

    // grab the application token
    $application_response = $httpClient->sendRequest($application_request);

    $application_response_body = json_decode((string) $application_response->getBody(), true);

    if (!$application_response_body || !isset($application_response_body['token'])) {
        fwrite(STDERR, 'Failed to initialize the Application.'.PHP_EOL);
        exit(1);
    }

    $application_token = $application_response_body['token'];

    // update the phpunit.xml from the phpunit.xml.dist
    $phpunit_dist = file_get_contents('phpunit.xml.dist');

    $updated_phpunit_config = str_replace('<env name="REGISTRY_URL" value=""/>', '<env name="REGISTRY_URL" value="http://127.0.0.1:8080/"/>', $phpunit_dist);
    $updated_phpunit_config = str_replace('<env name="APP_TOKEN" value=""/>', '<env name="APP_TOKEN" value="'.$application_token.'"/>', $updated_phpunit_config);

    file_put_contents('phpunit.xml', $updated_phpunit_config);
}

$body = json_encode($auth);

$request = $messageFactory->createRequest('POST', AUTH_ROUTE, [], $body);

$start = time();

while (true) {
    try {
        $response = $httpClient->sendRequest($request);

        if (200 === $response->getStatusCode()) {
            fwrite(STDOUT, 'Docker container started!'.PHP_EOL);

            $data = json_decode((string) $response->getBody(), true);
            $token = $data['token'] ?? null;

            initializeIntegrationTestsEnvironment($token, $httpClient, $messageFactory);
            exit(0);
        }
    } catch (RequestException $exception) {
        $elapsed = time() - $start;

        if ($elapsed > 30) {
            fwrite(STDERR, 'Docker container did not start in time...'.PHP_EOL);
            exit(1);
        }

        fwrite(STDOUT, 'Waiting for container to start...'.PHP_EOL);
        sleep(1);
    }
}
