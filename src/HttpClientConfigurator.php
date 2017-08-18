<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 8/18/17
 * Time: 5:20 AM
 */

namespace oneofftech\KRegistryClient;


use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;

class HttpClientConfigurator
{
    private $endpoint;

    private $token;

    private $httpClient;

    private $uriFactory;

    private $requestFactory;

    public function __construct(
        HttpClient $httpClient = null,
        UriFactory $uriFactory = null,
        RequestFactory $requestFactory = null
    ) {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? UriFactoryDiscovery::find();
        $this->requestFactory = $requestFactory ?? MessageFactoryDiscovery::find();
    }

    public function createConfiguredClient(): HttpClient
    {
        if (empty($this->endpoint)) {
            throw new \InvalidArgumentException('Unable to configure the client, no API Endpoint provided');
        }

        if ($this->token === null) {
            throw new \InvalidArgumentException('Unable to configure the client, no Token provided');
        }

        return $this->httpClient;
    }

    public function setEndpoint(string $endpoint): HttpClientConfigurator
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setToken(string $token): HttpClientConfigurator
    {
        $this->token = $token;

        return $this;
    }

}