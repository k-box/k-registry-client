<?php

namespace Tests\Unit;

use OneOffTech\KLinkRegistryClient\Api\AccessApi;
use OneOffTech\KLinkRegistryClient\Client;
use Tests\TestCase;

/**
 * @coversNothing
 */
class ClientTest extends TestCase
{
    public function test_client_can_be_instantiated()
    {
        $client = new Client(getenv('REGISTRY_URL'));

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(AccessApi::class, $client->access());
    }
}
