<?php

namespace OneOffTech\KLinkRegistryClient\Model;

/**
 * Application is information returned by the registry on success.
 *
 * Class ApplicationResponse
 */
class ApplicationResponse extends AbstractModel
{
    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->data['status'];
    }

    /**
     * @return string
     */
    public function getApplication(): string
    {
        return $this->data['application'];
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields()
    {
        return ['application', 'status'];
    }
}
