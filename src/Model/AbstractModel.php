<?php

declare(strict_types=1);

namespace OneOffTech\KLinkRegistryClient\Model;

/**
 * A base model that is extended by all other models.
 */
abstract class AbstractModel implements CreatableFromArray
{
    /**
     * Contains the information of the Model.
     *
     * @var array
     */
    protected $data;

    /**
     * Model constructor.
     *
     * @param array $data
     */
    protected function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function createFromArray(array $data)
    {
        $emptyModel = array_fill_keys(static::getFields(), null);

        // Fill all defined fields of the model, while discarding the undefined keys.
        $data = array_merge($emptyModel, array_intersect_key($data, $emptyModel));

        return new static($data);
    }

    // Allows subclasses to define allowed fields
    abstract protected static function getFields();
}
