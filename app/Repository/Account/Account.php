<?php

namespace Ebanx\Repository\Account;

use Ebanx\Libs\Config;
use Ebanx\Model\Account as AccountModal;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Account implements AccountContract
{
    protected AccountModal $model;

    public function __construct()
    {
        $this->model = new AccountModal();
    }

    #[Pure]
    public function getAll(): array
    {
        return $this->model->all();
    }

    /**
     * @param  string  $id
     * @return mixed
     * @throws \Exception
     */
    public function getById(string $id): mixed
    {
        $result = $this->model->find($id);

        if (is_null($result)) {
            throw new \Exception('Model Not Found', 404);
        }

        return array_values($result)[0];
    }

    /**
     * Create a new account
     *
     * @param  array  $attributes
     * @return array
     * @throws \Exception
     */
    public function create(array $attributes): array
    {
        return $this->model->firstOrCreate($attributes);
    }

    /**
     * @param  array  $model
     * @param  array  $attributes
     * @return array
     * @throws \Exception
     */
    public function update(array $model, array $attributes): array
    {
        foreach ($attributes as $key => $attribute) {
            if (in_array($key, array_keys($model)) && $model[$key] !== $attribute) {
                $model[$key] = $attribute;
            }
        }

        return $model;
    }

    public function reset()
    {
        $this->model->reset();
    }
}