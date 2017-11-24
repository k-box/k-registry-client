<?php

namespace OneOffTech\KLinkRegistryClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use OneOffTech\KLinkRegistryClient\Api\ApplicationApi;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
use OneOffTech\KLinkRegistryClient\Hydrator\ModelHydrator;

class ApiClient
{
    const VERSION = '1.0.0';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    public function __construct(HttpClient $httpClient = null, MessageFactory $messageFactory = null, Hydrator $modelHydrator = null)
    {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->messageFactory = $messageFactory ?? MessageFactoryDiscovery::find();
        $this->hydrator = $modelHydrator ?? new ModelHydrator();
    }

    /**
     * Returns configured Client from the given Configurator, MessageFactory and Hydrator.
     *
     * @param HttpClientConfigurator $httpClientConfigurator
     * @param MessageFactory|null    $messageFactory
     * @param Hydrator|null          $hydrator
     *
     * @return ApiClient
     */
    public static function fromConfigurator(
        HttpClientConfigurator $httpClientConfigurator,
        MessageFactory $messageFactory = null,
        Hydrator $hydrator = null
    ): self {
        $httpClient = $httpClientConfigurator->createConfiguredClient();

        return new self($httpClient, $messageFactory, $hydrator);
    }

    /**
     * @deprecated Use the ::application() call
     *
     * @return ApplicationApi
     */
    public function access(): ApplicationApi
    {
        return new ApplicationApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    /**
     * Returns a client to query the Application API endpoints.
     */
    public function application(): ApplicationApi
    {
        return new ApplicationApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }
}
