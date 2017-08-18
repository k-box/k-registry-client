<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 8/18/17
 * Time: 9:00 AM
 */

namespace oneofftech\KRegistryClient\Api;


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

    protected $hydrator;

    public function __construct(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        Hydrator $hydrator = null
    ) {
        $this->httpClient = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    private function createJsonBody(array $params) {
        if (count($params) === 0) {
            return null
        }

        return json_encode($params, empty($params) ? JSON_FORCE_OBJECT : 0);
    }

    private function buildPathFromParams(array $params) {
        if (count($params) > 0) {
            return '?'.http_build_query($params);
        }
    }

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

    protected function httpPostRaw(
        string $path,
        string $body,
        array $requestHeaders = []
    ): ResponseInterface {
        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('POST', $path, $requestHeaders, $body)
        );
    }

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