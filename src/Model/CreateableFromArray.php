<?php

declare(strict_types=1);

namespace oneofftech\KRegistryClient\Model;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface CreatableFromArray {
    /**
     * Create an API response object from the HTTP response from the API server.
     *
     * @param array $data
     * @return self
     */
    public static function createFromArray(array $data);
}
