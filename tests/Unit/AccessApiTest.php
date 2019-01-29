<?php

namespace OneOffTech\KLinkRegistryClient\Tests\Unit;

use OneOffTech\KLinkRegistryClient\Api\ApplicationApi;
use OneOffTech\KLinkRegistryClient\Exception\ApplicationVerificationException;
use OneOffTech\KLinkRegistryClient\Exception\HydrationException;
use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use OneOffTech\KLinkRegistryClient\Model\Application;

/**
 * Class ApplicationsTest.
 *
 * @covers \OneOffTech\KLinkRegistryClient\Api\ApplicationApi
 */
class AccessApiTest extends BaseHttpApiTest
{
    /**
     * @var ApplicationApi
     */
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = new ApplicationApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    public function testGetApplication()
    {
        $secret = 'secret';
        $appUrl = 'http://example.com/app/url';

        $this->configureMessage('POST', '/application.authenticate', json_encode([
            'id' => 'response-id',
            'params' => [
                'app_url' => $appUrl,
                'permissions' => [],
                'app_secret' => $secret,
            ],
        ]));
        $this->configureRequestAndResponse(200);
        $application = Application::createFromArray([
            'name' => 'Test Application',
            'app_url' => 'http://example.com/app/url',
            'app_id' => 1,
            'permissions' => ['PERMISSION-1'],
            'email' => 'email@example.com',
        ]);
        $this->configureHydrator(Application::class, $application);

        $retApplication = $this->client->getApplication($secret, $appUrl, [], 'response-id');
        $this->assertSame($application->getName(), $retApplication->getName());
        $this->assertSame($application->getEmail(), $retApplication->getEmail());
        $this->assertSame($application->getAppId(), $retApplication->getAppId());
        $this->assertSame($application->getAppUrl(), $retApplication->getAppUrl());
    }

    public function testGetApplicationWithEmptyAppUrl()
    {
        $secret = 'secret';
        $appUrl = '';

        $this->expectException(InvalidArgumentException::class);
        $this->client->getApplication($secret, $appUrl);
    }

    public function testGetApplicationWithEmptySecret()
    {
        $secret = '';
        $appUrl = 'http://example.com/app/url';

        $this->expectException(InvalidArgumentException::class);
        $this->client->getApplication($secret, $appUrl);
    }

    public function testGetApplicationWithEmptyAppUrlAndSecret()
    {
        $secret = '';
        $appUrl = '';

        $this->expectException(InvalidArgumentException::class);
        $this->client->getApplication($secret, $appUrl);
    }

    public function testGetApplicationWithNon200Response()
    {
        $secret = 'secret';
        $appUrl = 'http://example.com/app/url';

        $this->configureMessage('POST', '/application.authenticate', json_encode([
            'id' => 'response-id',
            'params' => [
                'app_url' => $appUrl,
                'permissions' => [],
                'app_secret' => $secret,
            ],
        ]));

        $this->configureRequestAndResponse(500);

        $this->expectException(ApplicationVerificationException::class);
        $this->expectExceptionMessage('Application cannot be verified. Please check secret and permissions');
        $this->client->getApplication($secret, $appUrl, [], 'response-id');
    }

    public function testGetApplicationWithHydratorError()
    {
        $secret = 'secret';
        $appUrl = 'http://example.com/app/url';

        $this->configureMessage('POST', '/application.authenticate', json_encode([
            'id' => 'response-id',
            'params' => [
                'app_url' => $appUrl,
                'permissions' => [],
                'app_secret' => $secret,
            ],
        ]));
        $this->configureRequestAndResponse(200);
        $this->hydrator->expects($this->once())
            ->method('hydrate')
            ->willThrowException(new HydrationException());

        $this->expectException(ApplicationVerificationException::class);
        $this->expectExceptionMessage('Application cannot be verified. Unexpected response from the server');

        $this->client->getApplication($secret, $appUrl, [], 'response-id');
    }

    public function testHasPermission()
    {
        $secret = 'secret';
        $appUrl = 'http://example.com/app/url';

        $this->configureMessage('POST', '/application.authenticate', json_encode([
            'id' => 'response-id',
            'params' => [
                'app_url' => $appUrl,
                'permissions' => ['PERM-1'],
                'app_secret' => $secret,
            ],
        ]));
        $this->configureRequestAndResponse(200);
        $application = Application::createFromArray([
            'name' => 'Test Application',
            'app_url' => 'http://example.com/app/url',
            'app_id' => 1,
            'permissions' => ['PERM-1'],
            'email' => 'email@example.com',
        ]);
        $this->configureHydrator(Application::class, $application);

        $ret = $this->client->hasPermissions($secret, $appUrl, ['PERM-1'], 'response-id');

        $this->assertTrue($ret);
    }

    public function testHasPermissionFalse()
    {
        $secret = 'secret';
        $appUrl = 'http://example.com/app/url';

        $this->configureMessage('POST', '/application.authenticate', json_encode([
            'id' => 'response-id',
            'params' => [
                'app_url' => $appUrl,
                'permissions' => ['PERM-2'],
                'app_secret' => $secret,
            ],
        ]));
        $this->configureRequestAndResponse(400);

        $ret = $this->client->hasPermissions($secret, $appUrl, ['PERM-2'], 'response-id');

        $this->assertFalse($ret);
    }

    public function testHandlesEmptyKlinks()
    {
        $secret = 'secret';
        $appUrl = 'http://example.com/app/url';

        $this->configureMessage('POST', '/application.authenticate', json_encode([
            'id' => 'response-id',
            'params' => [
                'app_url' => $appUrl,
                'permissions' => ['PERM-1'],
                'app_secret' => $secret,
            ],
        ]));
        $this->configureRequestAndResponse(200);
        $application = Application::createFromArray([
            'name' => 'Test Application',
            'app_url' => 'http://example.com/app/url',
            'app_id' => 1,
            'permissions' => ['PERM-1'],
            'email' => 'email@example.com',
            'klinks' => null,
        ]);
        $this->configureHydrator(Application::class, $application);

        $application = $this->client->getApplication($secret, $appUrl, ['PERM-1'], 'response-id');

        $this->assertEmpty($application->getKlinks());
    }
}
