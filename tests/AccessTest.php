<?php

namespace OneOffTech\KLinkRegistryClient\Tests;
use OneOffTech\KLinkRegistryClient\Api\AccessApi;


/**
 * Class ApplicationsTest
 * @covers \OneOffTech\KLinkRegistryClient\Api\AccessApi
 */
class AccessTest extends BaseApiTest
{

    /**
     * @var AccessApi
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new AccessApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    public function testAccessSuccess()
    {
    }

}