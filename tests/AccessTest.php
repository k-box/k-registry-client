<?php

namespace OneOffTech\KRegistryClient\Tests;
use OneOffTech\KRegistryClient\Api\AccessApi;


/**
 * Class ApplicationsTest
 * @covers \OneOffTech\KRegistryClient\Api\AccessApi
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
        $this->client->
    }


}