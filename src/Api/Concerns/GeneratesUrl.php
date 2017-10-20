<?php

namespace OneOffTech\KLinkRegistryClient\Api\Concerns;

trait GeneratesUrl
{
    private $url = null;

    /**
     * Set the common part of the URL. This is used to generate actions URLs @see url().
     *
     * @param string $url the base URL common to all requests
     *
     * @return mixed return $this for chaining up method calls
     */
    protected function setBaseUrl($url)
    {
        $this->url = rtrim(trim($url), '/');

        return $this;
    }

    /**
     * Generate the absolute URL for the specified action endpoint.
     *
     * @param string $action the action endpoint to generate the URL for
     *
     * @return string
     */
    protected function url($action)
    {
        return rtrim(trim($this->url), '/')."/api/1.0/$action";
    }
}
