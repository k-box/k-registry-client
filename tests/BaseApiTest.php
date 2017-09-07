<?php

namespace OneOffTech\KLinkRegistryClient\Tests;

use Http\Message\StreamFactory\GuzzleStreamFactory;
use OneOffTech\KLinkRegistryClient\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;

class BaseApiTest extends TestCase
{

    /** @var ResponseInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $response;

    /** @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $request;

    /** @var HttpClient|\PHPUnit_Framework_MockObject_MockObject */
    protected $httpClient;

    /** @var MessageFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $messageFactory;

    /** @var  Hydrator|\PHPUnit_Framework_MockObject_MockObject */
    protected $hydrator;

    public function setUp() {
        $this->response = $this->createMock(ResponseInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->messageFactory = $this->createMock(MessageFactory::class);
        $this->hydrator = $this->createMock(Hydrator::class);
    }

    public function configureMessage(string $action, string $uri, array $headers=[], string $body=null)
    {
        $this->messageFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with($action, $uri, $headers, $body)
            ->willReturn($this->request);
    }

    public function configureRequestAndResponse(int $responseCode, string $body='', array $headers=[], $contentType='application/json') {
        $this->response->method('getStatusCode')
            ->willReturn($responseCode);

        $bodyStream = (new GuzzleStreamFactory())->createStream($body);
        $this->response->method('getBody')
            ->willReturn($bodyStream);

        $this->response->method('getHeader')
            ->willReturnMap($headersMap);

        $this->response->method('getHeaderLine')
            ->with('Content-Type')
            ->willReturn($contentType);

        $this->httpClient->method('sendRequest')
            ->willReturn($this->response);
    }

    public function configureHydrator($class) {
        $this->hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->response, $class);
    }
}
