<?php

namespace Tests\Unit;

use OneOffTech\KLinkRegistryClient\Api\Concerns\GeneratesUrl;
use Tests\TestCase;

/**
 * @coversNothing
 */
class GeneratesUrlTest extends TestCase
{
    use GeneratesUrl;

    public function test_base_url_is_set()
    {
        $baseUrl = 'http://localhost/   ';
        $expectedBaseUrl = 'http://localhost';
        $returnValue = $this->setBaseUrl($baseUrl);

        $this->assertInstanceOf(self::class, $returnValue);
        $this->assertNotNull($this->url);
        $this->assertSame($expectedBaseUrl, $this->url);
    }

    public function test_url_construction()
    {
        $baseUrl = 'http://localhost';
        $action = 'application.authenticate';
        $expectedUrl = 'http://localhost/api/1.0/application.authenticate';
        $returnValue = $this->setBaseUrl($baseUrl)->url($action);

        $this->assertNotNull($returnValue);
        $this->assertSame($expectedUrl, $returnValue);
    }
}
