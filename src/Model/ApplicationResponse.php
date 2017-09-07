<?php

namespace OneOffTech\KLinkRegistryClient\Model;

/**
 * Application is information returned by the registry on success
 *
 * Class ApplicationResponse
 * @package OneOffTech\KLinkRegistryClient\Model
 */
class ApplicationResponse extends Model
{

    /**
     * @return int
     */
    public function getStatus(): int {
        return $this->data['status'];
    }

    /**
     * @return string
     */
    public function getApplication(): string {
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
