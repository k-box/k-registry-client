<?php

namespace Tests\Unit;

use Tests\BaseApiTestCase;
use OneOffTech\KLinkRegistryClient\Client;
use OneOffTech\KLinkRegistryClient\Api\AccessApi;
use OneOffTech\KLinkRegistryClient\Model\Application;
use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
use OneOffTech\KLinkRegistryClient\Hydrator\ModelHydrator;

/**
 * Class ApplicationsTest
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

        $this->client = new AccessApi(getenv('REGISTRY_URL'), $this->httpClient,  MessageFactoryDiscovery::find(), new ModelHydrator());
    }

    /**
     * @expectedException \OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException
     */
    public function test_invalid_argument_thrown_for_empty_token()
    {
        $application = $this->client->getApplication('', 'APP_URL', ['PERMISSION-1']);
    }

    /**
     * @expectedException \OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException
     */
    public function test_invalid_argument_thrown_for_empty_app_url()
    {
        $application = $this->client->getApplication('APP_TOKEN', '', ['PERMISSION-1']);
    }

    public function test_get_application()
    {
        $appUrl = 'http://localhost';

        $this->configureRequestAndResponse(200, \json_encode([
            'application_id' => 1, 'registrant_id' => 1, 'name' => 'Test Application', 'app_domain' => $appUrl, 'permissions' => ['PERMISSION-1']
        ]));

        $application = $this->client->getApplication('TOKEN', $appUrl, ['PERMISSION-1']);

        $this->assertInstanceOf(Application::class, $application);

        $this->assertEquals(1, $application->getApplicationId());
        $this->assertEquals('Test Application', $application->getName());
        $this->assertEquals($appUrl, $application->getAppDomain());
    }

    public function test_application_has_permission()
    {
        $this->configureRequestAndResponse(200, \json_encode([
            'application_id' => 1, 'registrant_id' => 1, 'name' => 'Test Application', 'app_domain' => 'http://localhost', 'permissions' => ['PERMISSION-1']
        ]));

        $hasPermission = $this->client->getApplication('TOKEN', 'http://localhost', ['PERMISSION-1']);

        $this->assertTrue($hasPermission);
    }

    public function test_application_dont_have_permission()
    {
        $this->configureRequestAndResponse(200, \json_encode(false));

        $hasPermission = $this->client->getApplication('TOKEN', 'http://localhost', ['PERMISSION-1']);

        $this->assertFalse($hasPermission);
    }

}