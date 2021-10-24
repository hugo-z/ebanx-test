<?php

namespace Ebanx\Services\Account;

interface AccountContract
{
    /**
     * @param  string  $id
     * @param  int $amount
     * @return array
     */
    public function deposit(string $id, int $amount): array;

    /**
     * @param  string  $id
     * @param  int  $amount
     * @return mixed
     */
    public function withdraw(string $id, int $amount): mixed;

    /**
     * @param  string  $origin
     * @param  string  $destination
     * @param  int  $amount
     * @return mixed
     */
    public function transfer(string $origin, string $destination, int $amount): mixed;
}