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
    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var UriFactory
     */
    private $uriFactory;

    /**
     * @var \Http\Message\MessageFactory|RequestFactory
     */
    private $requestFactory;

    /**
     * HttpClientConfigurator constructor.
     * @param HttpClient|null $httpClient
     * @param UriFactory|null $uriFactory
     * @param RequestFactory|null $requestFactory
     */
    public function __construct(
        HttpClient $httpClient = null,
        UriFactory $uriFactory = null,
        RequestFactory $requestFactory = null
    ) {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? UriFactoryDiscovery::find();
        $this->requestFactory = $requestFactory ?? MessageFactoryDiscovery::find();
    }

    /**
     * Creates a usable client from the API configuration
     * @return HttpClient
     */
    public function createConfiguredClient(): HttpClient
    {
        if (empty($this->endpoint)) {
            throw new \InvalidArgumentException('Unable to configure the client, no API Endpoint provided');
        }

        return $this->httpClient;
    }

    /**
     * setEndpoint changes the endpoint used for requests
     *
     * @param string $endpoint
     * @return HttpClientConfigurator
     */
    public function setEndpoint(string $endpoint): HttpClientConfigurator
    {
        $this->endpoint = $endpoint;

        return $this;
    }

}