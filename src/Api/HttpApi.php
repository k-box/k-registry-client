<?php

namespace OneOffTech\KLinkRegistryClient\Api;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
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

    /**
     * @var Hydrator
     */
    protected $hydrator;

    public function __construct(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        Hydrator $hydrator
    ) {
        $this->httpClient = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->hydrator = $hydrator;
    }

    /**
     * Send a JSON RPC request via POST.
     *
     * @param string      $path
     * @param array       $params
     * @param string|null $requestId the RequestId to send, if null it will be auto-generated
     *
     * @return ResponseInterface
     */
    protected function httpRpcPost(
        string $path,
        array $params = [],
        string $requestId = null
    ): ResponseInterface {
        $body = $this->createJsonRpcBody($params, $requestId);

        return $this->httpClient->sendRequest(
            $this->messageFactory->createRequest('POST', $path, [], $body)
        );
    }

    /**
     * Creates a timestamp from the microseconds of the request.
     *
     * @return string
     */
    private function generateRequestId()
    {
        // We must use a numeric value here, nothing fancy as 'kregistryclient-%d' due to a bug in the KRegistry API
        return sprintf('%d', microtime(true) * 10000);
    }

    /**
     * Creates a json encoded request body from the supplied parameters.
     *
     * @param array       $params
     * @param string|null $requestId the RequestId to send, if null it will be auto-generated
     *
     * @return null|string
     */
    private function createJsonRpcBody(array $params, string $requestId = null)
    {
        if (0 === count($params)) {
            return null;
        }

        // Prepare JRPC request, use timestamp as an ID (so that we can
        // later debug access times)
        $payload = [
            'id' => $requestId ?? $this->generateRequestId(),
            'params' => $params,
        ];

        return json_encode($payload, empty($payload) ? JSON_FORCE_OBJECT : 0);
    }
}
