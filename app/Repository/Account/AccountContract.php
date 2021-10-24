<?php

namespace Ebanx\Repository\Account;

interface AccountContract
{
    /**
     * @return mixed
     */
    public function getAll(): mixed;

    /**
     * @param  string  $id
     * @return mixed
     */
    public function getById(string $id): mixed;

    /**
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed;

    /**
     * @param  array  $model
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $model, array $attributes): mixed;

    public function reset();
}