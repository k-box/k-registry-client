<?php

declare(strict_types=1);

namespace OneOffTech\KLinkRegistryClient;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\UriFactory;

/**
 * Configure an HTTP client.
 */
final class HttpClientConfigurator
{
    /**
     * Specify the API uri.
     */
    const API_URI = '/api/1.0';

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
     * @var Plugin[]
     */
    private $prependPlugins = [];

    /**
     * @var Plugin[]
     */
    private $appendPlugins = [];

    public function __construct(HttpClient $httpClient = null, UriFactory $uriFactory = null)
    {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? UriFactoryDiscovery::find();
    }

    public function createConfiguredClient(): HttpClient
    {
        if (empty($this->endpoint)) {
            throw new \InvalidArgumentException('Unable to configure the client, no KRegistry API Endpoint provided');
        }

        $plugins = $this->prependPlugins;
        $plugins[] = new Plugin\BaseUriPlugin($this->uriFactory->createUri($this->endpoint.self::API_URI));
        $plugins[] = new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => sprintf('KLinkRegistry Client v%s', ApiClient::VERSION),
        ]);

        return new PluginClient($this->httpClient, array_merge($plugins, $this->appendPlugins));
    }

    /**
     * Set the KRegistry API endpoint.
     *
     * @param string $endpoint
     *
     * @return self
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @param Plugin|Plugin[] ...$plugin
     *
     * @return self
     */
    public function appendPlugin(Plugin ...$plugin): self
    {
        foreach ($plugin as $p) {
            $this->appendPlugins[] = $p;
        }

        return $this;
    }

    /**
     * @param Plugin|Plugin[] ...$plugin
     *
     * @return self
     */
    public function prependPlugin(Plugin ...$plugin): self
    {
        $plugin = array_reverse($plugin);
        foreach ($plugin as $p) {
            array_unshift($this->prependPlugins, $p);
        }

        return $this;
    }
}
