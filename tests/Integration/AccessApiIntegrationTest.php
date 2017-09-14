<?php

namespace Tests\Unit;

use Tests\TestCase;
use OneOffTech\KLinkRegistryClient\Client;
use OneOffTech\KLinkRegistryClient\Model\Application;


/**
 * @group integration
 */
class AccessApiIntegrationTest extends TestCase
{

    /**
     * @var AccessApi
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = (new Client(getenv('REGISTRY_URL')))->access();

        $this->appToken = getenv('APP_TOKEN');
        $this->appUrl = getenv('APP_URL');
        $this->appPermissions = explode(',', getenv('APP_PERMISSIONS'));
    }

    public function test_get_application()
    {
        $application = $this->client->getApplication($this->appToken, $this->appUrl, $this->appPermissions);

        $this->assertInstanceOf(Application::class, $application);

        $this->assertNotEmpty($application->getApplicationId());
        $this->assertNotEmpty($application->getName());
        $this->assertNotEmpty($application->getAppDomain());
    }

    public function test_application_has_permission()
    {
        $hasPermission = $this->client->hasPermissions($this->appToken, $this->appUrl, $this->appPermissions);

        $this->assertTrue($hasPermission);
    }
    
    public function test_application_dont_have_permission()
    {
        $hasPermission = $this->client->hasPermissions($this->appToken, $this->appUrl, ['something-that-is-unlikely-to-exists']);

        $this->assertFalse($hasPermission);
    }

}