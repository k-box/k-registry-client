<?php

declare(strict_types=1);

namespace oneofftech\KRegistryClient\Model;

/*
 * A base model that is extended by all other models
 */
abstract class Model implements creatableFromArray
{
    /**
     * Contains the information of the Model
     *
     * @var array
     */
    protected $data;

    /**
     * Model constructor.
     * @param array $data
     */
    protected function __construct(array $data)
    {
        $this->data = $data
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data) {
        $emptyModel = array_fill_keys(static::getFields(), null);

        // fill all defined fields of the model, while discarding the undefined keys.
        $data = array_merge($emptyModel, array_intersect_key($data, $emptyModel));

        return new static($data);
    }

    // allows subclasses to define allowed fields
    abstract protected static function getFields();
}