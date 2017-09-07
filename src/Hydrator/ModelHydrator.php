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
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') !== 0) {
            throw new HydrationException('The ModelHydrator cannot hydrate with content-type of: '.$response->getHeaderLine('Content-Type'));
        }

        // deserialize into array
        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HydrationException('Error when trying to decode response');
        }

        if (is_subclass_of($class, CreatableFromArray::class)) {
            $object = call_user_func($class.'::createFromArray', $data);
        } else {
            $object = new $class($data);
        }

        return $object;
    }

}
