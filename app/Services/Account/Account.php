<?php

namespace Ebanx\Services\Account;

use Ebanx\Model\Account as AccountModel;
use Ebanx\Services\Service;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Account extends Service implements AccountContract
{
    protected AccountModel $model;

    const ACTIONS = [
        'deposit',
        'withdraw',
        'transfer'
    ];

    public function __construct()
    {
        $this->model = new AccountModel();
    }

    /**
     * @param  string  $name
     * @param  array  $arguments
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (!in_array($name, self::ACTIONS)) {
            throw new \Exception('Bad Request', 400);
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function getAccount($id): mixed
    {
        return $this->model->find($id)->getAttributes('balance');
    }

    /**
     * @return array
     */
    public function getAllAccounts(): array
    {
        $allAccounts = $this->model->all();

        return array_map(function ($account) {
            return $account->getAttributes();
        }, $allAccounts);
    }

    /**
     * @param  ...$attributes
     * @return mixed
     * @throws \Exception
     */
    #[ArrayShape(['destination' => "mixed"])]
    public function deposit(...$attributes): array
    {
        // Validate incoming queries
        $validated = $this->validateQueries(
            ['destination', 'amount'],
            array_keys($attributes[0])
        );

        if ($validated) {
            try {
                $account = $this->model->find($attributes[0]['destination']);

                $updated = $this->model->update(
                    [
                        'id' => $account->getAttributes('id'),
                        'balance' => (int)$account->getAttributes('balance') + (int)$attributes[0]['amount']
                    ],
                    'id'
                );

                return ['destination' => $updated->getAttributes()];
            } catch (\Exception $e) {
                if (404 === $e->getCode()) {
                    $created = $this->model->create([
                        'id' => (string)$attributes[0]['destination'],
                        'balance' => $attributes[0]['amount']
                    ]);

                    return ['destination' => $created->getAttributes()];
                }

                // Log the error
            }
        }
    }

    /**
     * @param  array  ...$attributes
     * @return array
     * @throws \Exception
     */
    public function withdraw(...$attributes): array
    {
        // Validate incoming queries
        $validated = $this->validateQueries(
            ['origin', 'amount'],
            array_keys($attributes[0])
        );

        if ($validated) {
            $account = $this->model->find($attributes[0]['origin']);

            if ((int)$account->getAttributes('balance') < (int)$attributes[0]['amount']) {
                throw new \Exception('Amount Exceeded', 422);
            }

            $updated = $this->model->update(
                [
                    'id' => $account->getAttributes('id'),
                    'balance' => (int)$account->getAttributes('balance') - (int)$attributes[0]['amount']
                ],
                'id'
            );

            return ['origin' => $updated->getAttributes()];
        }
    }

    /**
     * @param  array  ...$attributes
     * @return array
     * @throws \Exception
     */
    public function transfer(...$attributes): array
    {
        // Validate incoming queries
        $validated = $this->validateQueries(
            ['origin', 'destination', 'amount'],
            array_keys($attributes[0])
        );

        if ($validated) {
            $origin = (new AccountModel())->find($attributes[0]['origin']);
            $destination = (new AccountModel())->find($attributes[0]['destination']);

            if ((int)$origin->getAttributes('balance') < (int)$attributes[0]['amount']) {
                throw new \Exception('Amount Exceeded', 422);
            }

            $originBalance = (int)$origin->getAttributes('balance') - (int)$attributes[0]['amount'];
            $destBalance = (int)$destination->getAttributes('balance') + (int)$attributes[0]['amount'];

            $updatedOrigin = $origin->update(
                [
                    'id' => $origin->getAttributes('id'),
                    'balance' => $originBalance
                ],
                'id'
            );

            $updatedDest = $destination->update(
                [
                    'id' => $destination->getAttributes('id'),
                    'balance' => $destBalance
                ],
                'id'
            );

            return [
                'origin' => $updatedOrigin->getAttributes(),
                'destination' => $updatedDest->getAttributes()
            ];
        }
    }

    public function reset()
    {
        $this->model->initDb();
    }
}