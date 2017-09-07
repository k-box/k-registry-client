<?php

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
use OneOffTech\KLinkRegistryClient\Hydrator\ModelHydrator;
use OneOffTech\KLinkRegistryClient\Api\AccessApi;

class KRegistryClient {
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
     * KRegistryClient constructor.
     * @param HttpClient|null $httpClient
     * @param MessageFactory|null $messageFactory
     * @param Hydrator|null $hydrator
     */
    public function __construct(
        HttpClient $httpClient=null,
        MessageFactory $messageFactory=null,
        Hydrator $hydrator=null
    ) {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    public function access(): AccessApi
    {
        $access = new AccessApi($this->httpClient, $this->messageFactory, $this->hydrator);

        return $access;
    }
}
