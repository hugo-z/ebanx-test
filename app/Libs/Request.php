<?php

namespace Ebanx\Libs;

class Request
{
    protected array $queryArray = [];

    /**
     * Construction method
     */
    public function __construct()
    {
        $this->parseRequestedParams();
    }

    /**
     * Parse the requested params or data
     */
    private function parseRequestedParams()
    {
        $this->queryArray = empty($_REQUEST)
            ? !json_decode(file_get_contents("php://input"), true)
                ? []
                : json_decode(file_get_contents("php://input"), true)
            : $_REQUEST;
    }


    /**
     * Return all
     *
     * @return array
     */
    public function all(): array
    {
        return $this->queryArray;
    }

    /**
     * Return request data by given key
     *
     * @param  string  $key
     * @return mixed
     */
    public function input(string $key): mixed
    {
        if (array_key_exists($key, $this->queryArray)) {
            return $this->queryArray[$key];
        }

        return null;
    }

    /**
     * @param ...$attributes
     * @return array
     */
    public function except(...$attributes): array
    {
        $sortQuery = [];

        if (!empty($this->queryArray)) {

            array_walk($this->queryArray, function ($param, $key) use (&$sortQuery, $attributes) {
                if (!in_array($key, $attributes)) {
                    $sortQuery[$key] = $param;
                }
            });
        }

        return $sortQuery;
    }
}