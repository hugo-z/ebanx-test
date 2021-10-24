<?php

namespace Ebanx\Model\Base;

class Model
{
    /**
     * @var string
     */
    protected string $tableName = '';

    /**
     * @var array
     */
    protected array $fillable = [];

    /**
     * Model attributes array
     *
     * @var array
     */
    protected array $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    protected function setAttributes(array $attributes): Model
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param  string|null  $needle
     * @return mixed
     */
    public function getAttributes(string|null $needle = null): mixed
    {
        if (is_null($needle)) {
            return $this->attributes;
        }

        return $this->attributes[$needle];
    }

    /**
     * @param  array  $searchArray
     * @param  array|string  $needle
     * @param  string  $needleKey
     * @return array|null
     */
    public function existed(array $searchArray, array|string $needle, string $needleKey = 'id'): array|null
    {
        $searchResult = array_filter($searchArray, function ($item) use ($needle, $needleKey) {
            if (is_array($needle) && !is_null($needleKey)) {
                return $item[$needleKey] == $needle[$needleKey];
            }

            return $item[$needleKey] == $needle;
        });

        return !empty($searchResult) ? array_values($searchResult) : null;
    }

    /**
     * @param  array  $attributes
     * @throws \Exception
     */
    protected function checkFillable(array $attributes): void
    {
        if ($diff = array_diff(array_keys($attributes), $this->fillable) && !empty($diff)) {
            $unWanted = implode(',', $diff);
            throw new \Exception('Unwanted attribute(s): ' . $unWanted, 422);
        }
    }
}