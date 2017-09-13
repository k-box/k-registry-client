<?php

namespace Tests\Unit;

use Tests\TestCase;
use OneOffTech\KLinkRegistryClient\Client;
use OneOffTech\KLinkRegistryClient\Api\Concerns\GeneratesUrl;

class GeneratesUrlTest extends TestCase
{
    use GeneratesUrl;

    public function test_base_url_is_set()
    {
        $baseUrl = 'http://localhost/   ';
        $expectedBaseUrl = 'http://localhost';
        $returnValue = $this->setBaseUrl($baseUrl);

        $this->assertInstanceOf(GeneratesUrlTest::class, $returnValue);
        $this->assertNotNull($this->url);
        $this->assertEquals($expectedBaseUrl, $this->url);
    }

    public function test_url_construction()
    {
        $baseUrl = 'http://localhost';
        $action = 'application/access/';
        $expectedUrl = 'http://localhost/application/access/';
        $returnValue = $this->setBaseUrl($baseUrl)->url($action);

        $this->assertNotNull($returnValue);
        $this->assertEquals($expectedUrl, $returnValue);
    }
    
}
