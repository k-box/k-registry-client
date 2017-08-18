<?php

namespace oneofftech\KRegistryClient;


use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;
use oneofftech\KRegistryClient\Hydrator\Hydrator;
use oneofftech\KRegistryClient\Hydrator\ModelHydrator;

final class ApiClient
{
    private $httpClient

    private $hydrator

    private $messageFactory

    public function __construct(
        HttpClient $httpClient = null,
        MessageFactory $messageFactory = null,
        Hydrator $hydrator = null
    ) {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();
        $this->hydrator = $hydrator :? new ModelHydrator();
    }

    public static function configure(
        HttpClientConfigurator $httpClientConfigurator,
        RequestFactory $requestFactory = null,
        Hydrator $hydrator = null
    ): self {
        $httpClient = $httpClientConfigurator->createConfiguredClient();

        return new self($httpClient, $requestFactory, $hydrator);
    }
}