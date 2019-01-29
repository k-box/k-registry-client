<?php

namespace OneOffTech\KLinkRegistryClient\Tests\Unit;

use OneOffTech\KLinkRegistryClient\Api\ApplicationApi;
use OneOffTech\KLinkRegistryClient\ApiClient;
use OneOffTech\KLinkRegistryClient\HttpClientConfigurator;
use OneOffTech\KLinkRegistryClient\Model\Application;
use OneOffTech\KLinkRegistryClient\Model\Klink;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 * @coversNothing
 */
class AccessApiIntegrationTest extends TestCase
{
    /** @var ApplicationApi */
    private $applicationApi;

    /** @var string */
    private $appToken;

    /** @var string */
    private $appUrl;

    /** @var array */
    private $appPermissions;

    public function setUp()
    {
        $configurator = new HttpClientConfigurator();
        $configurator->setEndpoint(getenv('REGISTRY_URL'));

        $this->applicationApi = ApiClient::fromConfigurator($configurator)->application();

        $this->appToken = getenv('APP_TOKEN');
        $this->appUrl = getenv('APP_URL');
        $this->appPermissions = explode(',', getenv('APP_PERMISSIONS'));
    }

    public function testGetApplication()
    {
        $application = $this->applicationApi->getApplication($this->appToken, $this->appUrl, $this->appPermissions);

        $this->assertInstanceOf(Application::class, $application);

        $this->assertNotEmpty($application->getAppId());
        $this->assertNotEmpty($application->getName());
        $this->assertNotEmpty($application->getKlinks());
        $this->assertContainsOnlyInstancesOf(Klink::class, $application->getKlinks());
    }

    public function testApplicationHasPermission()
    {
        $hasPermission = $this->applicationApi->hasPermissions($this->appToken, $this->appUrl, $this->appPermissions);

        $this->assertTrue($hasPermission);
    }

    public function testApplicationDontHavePermission()
    {
        $hasPermission = $this->applicationApi->hasPermissions(
            $this->appToken,
            $this->appUrl,
            ['something-that-is-unlikely-to-exists']
        );

        $this->assertFalse($hasPermission);
    }
}
