<?php

namespace OneOffTech\KLinkRegistryClient\Api;


use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use oneofftech\KRegistryClient\Hydrator\Hydrator;
use oneofftech\KRegistryClient\Hydrator\ModelHydrator;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
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
     * @param HttpClient $httpClient
     * @param MessageFactory $messageFactory
     * @param Hydrator|null $hydrator
     */
    public function __construct(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        Hydrator $hydrator = null
    ) {
        $this->httpClient = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    /**
     * Creates a json encoded request body from the supplied parameters.
     *
     * @param array $params
     * @return null|string
     */
    private function createJsonBody(array $params) {
        if (count($params) === 0) {
            return null;
        }

        return json_encode($params, empty($params) ? JSON_FORCE_OBJECT : 0);
    }

    /**
     * Builds the path in the format of '?key1=value1&key2=value2' from the supplied
     * parameters, returns null if empty.
     *
     * @param array $params
     * @return string
     */
    private function buildPathFromParams(array $params) {
        if (count($params) > 0) {
            return '?'.http_build_query($params);
        }
    }

    /**
     * Send a GET request with the parameters
     *
     * @param string $path
     * @param array $params
     * @param array $requestHeaders
     * @return ResponseInterface
     */
    protected function httpGet(
        string $path,
        array $params = [],
        array $requestHeaders = []
    ):  ResponseInterface {
        $path = $this->buildPathFromParams($params);

        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('GET', $path, $requestHeaders)
        );
    }

    /**
     * Send a POST request with a raw body
     *
     * @param string $path
     * @param string $body
     * @param array $requestHeaders
     * @return ResponseInterface
     */
    protected function httpPostRaw(
        string $path,
        string $body,
        array $requestHeaders = []
    ): ResponseInterface {
        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('POST', $path, $requestHeaders, $body)
        );
    }

    /**
     * Send a POST request with a JSON-encoded body
     *
     * @param string $path
     * @param array $params
     * @param array $pathParams
     * @param array $requestHeaders
     * @return ResponseInterface
     */
    protected function httpPost(
        string $path,
        array $params = [],
        array $pathParams = [],
        array $requestHeaders = []
    ): ResponseInterface {
        $path = $this->buildPathFromParams($pathParams);
        $body = $this->createJsonBody($params);
        return $this->httpPostRaw($path, $body);
    }
}