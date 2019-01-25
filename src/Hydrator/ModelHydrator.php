<?php

namespace OneOffTech\KLinkRegistryClient\Hydrator;

use OneOffTech\KLinkRegistryClient\Exception\HydrationException;
use OneOffTech\KLinkRegistryClient\Model\CreatableFromArray;
use Psr\Http\Message\ResponseInterface;

/*
 * A ModelHydrator builds a model from a JSON body
 */
class ModelHydrator implements Hydrator
{
    public function hydrate(ResponseInterface $response, string $class)
    {
        $body = (string) $response->getBody();
        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new HydrationException('The ModelHydrator cannot hydrate with content-type of: '.$response->getHeaderLine('Content-Type'));
        }

        // deserialize into array
        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HydrationException('Error when trying to decode response');
        }

        if (false === $data) {
            throw new HydrationException('Unexpected response, no response found');
        }

        if (isset($data['error'])) {
            // if the error key exists, the request was not valid.
            // output the error message
            throw new HydrationException('Error response: '.$data['error']['message']);
        }

        if (is_subclass_of($class, CreatableFromArray::class)) {
            $object = \call_user_func($class.'::createFromArray', $data['result']);
        } else {
            $object = new $class($data);
        }

        return $object;
    }
}
