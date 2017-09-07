<?php

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
use OneOffTech\KLinkRegistryClient\Hydrator\ModelHydrator;
use OneOffTech\KLinkRegistryClient\Api\ApplicationsApi;

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

    public function __construct(
        HttpClient $httpClient=null,
        MessageFactory $messageFactory=null,
        Hydrator $hydrator=null
    ) {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    public function application(string $token): ApplicationsApi
    {
        $application = new Api\Applications($this->httpClient, $this->messageFactory, $this->hydrator);
        $application->setToken($token);

        return $application;
    }
}
