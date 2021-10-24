<?php

namespace Ebanx\Model\Base;

interface ModelContract
{
    public function find(string $id);

    public function all();

    public function create(array $attributes);

    public function update(array $attributes, string $needle);

    public function firstOrCreate(array $attributes);
}