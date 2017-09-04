<?php

namespace OneOffTech\KRegistryClient\Hydrator;


use Psr\Http\Message\ResponseInterface;

interface Hydrator
{
    public function hydrate(ResponseInterface $response, string $class);
}
