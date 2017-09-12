<?php

namespace OneOffTech\KLinkRegistryClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
use OneOffTech\KLinkRegistryClient\Hydrator\ModelHydrator;
use OneOffTech\KLinkRegistryClient\Api\AccessApi;

class Client {

    private $url = null;

    private $httpClient;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var RequestFactory
     */
    private $messageFactory;

    /**
     * K-Registry Client constructor.
     *
     * @param string $url The URL of the K-Registry that will be used by this client
     * @return OneOffTech\KLinkRegistryClient\Client;
     */
    public function __construct(
        string $url
    ) {
        $this->url = $url;
        $this->httpClient = HttpClientDiscovery::find();
        $this->messageFactory = MessageFactoryDiscovery::find();
        $this->hydrator = new ModelHydrator();
    }

    public function access(): AccessApi
    {
        $access = new AccessApi($this->url, $this->httpClient, $this->messageFactory, $this->hydrator);

        return $access;
    }
    
}
