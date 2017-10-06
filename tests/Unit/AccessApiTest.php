<?php

namespace Tests\Unit;

use Http\Discovery\MessageFactoryDiscovery;
use OneOffTech\KLinkRegistryClient\Api\AccessApi;
use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use OneOffTech\KLinkRegistryClient\Hydrator\ModelHydrator;
use OneOffTech\KLinkRegistryClient\Model\Application;
use Tests\BaseApiTestCase;

/**
 * Class ApplicationsTest.
 *
 * @covers \OneOffTech\KLinkRegistryClient\Api\AccessApi
 */
class AccessApiTest extends BaseApiTestCase
{
    /**
     * @var AccessApi
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new AccessApi(getenv('REGISTRY_URL'), $this->httpClient, MessageFactoryDiscovery::find(), new ModelHydrator());
    }

    public function test_invalid_argument_thrown_for_empty_token()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getApplication('', 'APP_URL', ['PERMISSION-1']);
    }

    public function test_invalid_argument_thrown_for_empty_app_url()
    {
        $this->expectException(InvalidArgumentException::class);
        $application = $this->client->getApplication('APP_TOKEN', '', ['PERMISSION-1']);
    }

    public function test_get_application()
    {
        $appUrl = 'https://localhost';

        $this->configureRequestAndResponse(200,
        '{"id":"0","result":{"name":"Test Application","app_url":"https:\/\/localhost","app_id":1,"permissions":["PERMISSION-1"],"email":"admin@oneofftech.xyz"}}'
        );

        $application = $this->client->getApplication('TOKEN', $appUrl, ['PERMISSION-1']);

        $this->assertInstanceOf(Application::class, $application);

        $this->assertSame(1, $application->getAppId());
        $this->assertSame('Test Application', $application->getName());
        $this->assertSame($appUrl, $application->getAppUrl());
        $this->assertSame('admin@oneofftech.xyz', $application->getEmail());
    }

    public function test_application_has_permissions()
    {
        $this->configureRequestAndResponse(200,
            '{"id":"0","result":{"name":"Test Application","app_url":"https:\/\/localhost","app_id":1,"permissions":["PERMISSION-1", "PERMISSION-2"],"email":"admin@oneofftech.xyz"}}'
        );

        $hasPermissions = $this->client->hasPermissions('TOKEN', 'http://localhost', ['PERMISSION-2']);

        $this->assertTrue($hasPermissions);
    }

    public function test_application_dont_have_permissions()
    {
        $this->configureRequestAndResponse(400,
            '{"id":"0","result":{"name":"Test Application","app_url":"https:\/\/localhost","app_id":1,"permissions":["PERMISSION-1", "PERMISSION-2"],"email":"admin@oneofftech.xyz"}}'
        );

        $hasPermissions = $this->client->hasPermissions('TOKEN', 'http://localhost', ['PERMISSION-3']);

        $this->assertFalse($hasPermissions);
    }
}
