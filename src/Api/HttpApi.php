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
     * @param HttpClient $httpClient
     * @param MessageFactory $messageFactory
     * @param Hydrator|null $hydrator
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

        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('GET', $path, $requestHeaders)
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
        array $requestHeaders = []
    ): ResponseInterface {
        $body = $this->createJsonBody($params);
        
        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('POST', $path, $requestHeaders, $body)
        );
    }

}