<?php

namespace OneOffTech\KRegistryClient\Model;

/**
 * Application is information returned by the registry on success
 *
 * Class Application
 * @package OneOffTech\KRegistryClient\Model
 */
class Application extends Model
{

    /**
     * @return int
     */
    public function getApplicationId(): int {
        return $this->data['application_id'];
    }

    /**
     * @return string
     */
    public function getRegistrantId(): string {
        return $this->data['registrant_id'];
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->data['name'];
    }

    /**
     * @return string
     */
    public function getAppDomain(): string {
        return $this->data['app_domain'];
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields()
    {
        return ['application_id', 'registrant_id', 'name', 'app_domain'];
    }
}
