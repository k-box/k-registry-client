<?php

namespace OneOffTech\KLinkRegistryClient\Api;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
use OneOffTech\KLinkRegistryClient\Hydrator\ModelHydrator;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
    use Concerns\GeneratesUrl;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /*
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * HttpApi constructor.
     *
     * @param HttpClient     $httpClient
     * @param MessageFactory $messageFactory
     * @param Hydrator|null  $hydrator
     */
    public function __construct(
        string $url,
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        Hydrator $hydrator = null
    ) {
        $this->httpClient = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->hydrator = $hydrator ?: new ModelHydrator();
        $this->setBaseUrl($url);
    }

    /**
     * Send a GET request with the parameters.
     *
     * @param string $path
     * @param array  $params
     * @param array  $requestHeaders
     *
     * @return ResponseInterface
     */
    protected function httpGet(
        string $path,
        array $params = [],
        array $requestHeaders = []
    ): ResponseInterface {
        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('GET', $path, $requestHeaders)
        );
    }

    /**
     * Send a POST request with a JSON-encoded body.
     *
     * @param string $path
     * @param array  $params
     * @param array  $pathParams
     * @param array  $requestHeaders
     *
     * @return ResponseInterface
     */
    protected function httpPost(
        string $path,
        array $params = [],
        array $requestHeaders = []
    ): ResponseInterface {
        $body = $this->createJsonBody($params);

        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('POST', $path, $requestHeaders, $body)
        );
    }

    /**
     * Creates a timestamp from the microseconds of the request.
     *
     * @return string
     */
    private function timestampId()
    {
        return number_format(microtime(true) * 10000, 0, '.', '');
    }

    /**
     * Creates a json encoded request body from the supplied parameters.
     *
     * @param array $params
     *
     * @return null|string
     */
    private function createJsonBody(array $params)
    {
        if (0 === count($params)) {
            return null;
        }

        // Prepare JRPC request, use timestamp as an ID (so that we can
        // later debug access times)
        $payload = ['id' => $this->timestampId(), 'params' => $params];

        return json_encode($payload, empty($payload) ? JSON_FORCE_OBJECT : 0);
    }
}
