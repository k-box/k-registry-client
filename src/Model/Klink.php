<?php

namespace OneOffTech\KLinkRegistryClient\Model;

/**
 * Klink information.
 */
class Klink extends AbstractModel
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->data['name'];
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields()
    {
        return ['name', 'id'];
    }
}
