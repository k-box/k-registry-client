<?php

namespace oneofftech\KRegistryClient\Model;


class Application extends Model
{

    public function getApplicationId(): int {
        return $this->data['application_id'];
    }

    public function getRegistrantId(): string {
        return $this->data['registrant_id'];
    }

    public function getName(): string {
        return $this->data['name'];
    }

    public function getAppDomain(): string {
        return $this->data['app_domain'];
    }

    protected static function getFields()
    {
        return ['application_id', 'registrant_id', 'name', 'app_domain'];
    }
}