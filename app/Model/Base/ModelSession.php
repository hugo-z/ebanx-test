<?php

namespace Ebanx\Model\Base;

use Exception;
use Traversable;

class ModelSession extends Model implements ModelContract
{
    public function __construct(array $attributes = [])
    {
        @session_start();
        parent::__construct($attributes);
    }

    /**
     * Fetch one record with id
     *
     * @param  string  $id
     * @return ModelSession|null
     * @throws Exception
     */
    public function find(string $id): ModelSession|null
    {
        if (isset($_SESSION[$this->tableName]) && !empty($_SESSION[$this->tableName])) {
            $record = $this->existed($_SESSION[$this->tableName], $id);

            if (is_null($record)) {
                throw new Exception('Model Not Found', 404);
            }

            return $this->setAttributes($record[0]);
        }

    }

    /**
     * Fetch all records
     *
     * @return array
     */
    public function all(): array
    {
        return array_map(function ($item) {
            return new self($item);
        }, $_SESSION[$this->tableName]);
    }

    /**
     * @param  array  $attributes
     * @return ModelSession
     * @throws \Exception
     */
    public function create(array $attributes): ModelSession
    {
        $this->checkFillable($attributes);
        array_push($_SESSION[$this->tableName], $attributes);

        return $this->setAttributes($attributes);
    }

    /**
     * Create a record if none exists
     *
     * @param  array  $attributes
     * @param  string  $needle
     * @return ModelSession
     * @throws \Exception
     */
    public function firstOrCreate(array $attributes, string $needle = 'id'): ModelSession
    {
        $this->checkFillable($attributes);
        $existResult = $this->existed($_SESSION[$this->tableName], $attributes, $needle);

        if (empty($existResult)) {
            array_push($_SESSION[$this->tableName], $attributes);

            return $this->setAttributes($attributes);
        }

        return $this->setAttributes($existResult[0]);
    }

    /**
     * @param  array  $attributes
     * @param  string  $needle
     * @return $this|int
     * @throws Exception
     */
    public function update(array $attributes, string $needle = 'id'): int|static
    {
        $this->checkFillable($attributes);

        $existedItem = $this->existed($_SESSION[$this->tableName], $attributes, $needle);

        if (is_array($existedItem) && !empty($existedItem)) {
            $updatedArray = array_map(function ($item) use ($needle, $attributes) {
                if ($item[$needle] === $attributes[$needle]) {
                    $item = $attributes;
                }

                return $item;
            }, $_SESSION[$this->tableName]);

//            dump($updatedArray);

            $_SESSION[$this->tableName] = $updatedArray;

            return $this->setAttributes($attributes);
        }

        return 0;
    }
}