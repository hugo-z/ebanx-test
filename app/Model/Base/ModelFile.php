<?php

namespace Ebanx\Model\Base;

class ModelFile extends Model implements ModelContract
{
    protected string $tablePath;

    /**
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->initTable();
    }

    public function initTable()
    {
        if (!file_exists(CACHE_PATH . $this->tableName)) {
            mkdir(CACHE_PATH . $this->tableName);
        }

        $this->tablePath = CACHE_PATH . $this->tableName;
    }

    /**
     * @param  string  $id
     * @return ModelFile|null
     * @throws \Exception
     */
    public function find(string $id): ModelFile|null
    {
        $accountFiles = $this->getAllAccountFiles();

        if (empty($accountFiles)) {
            throw new \Exception('Model Not Found', 404);
        }

        $accountIds = array_map(function ($fileName) {
            return explode('.', $fileName)[0];
        }, $accountFiles);

        if (in_array($id, $accountIds)) {
            $accountInfo = unserialize(file_get_contents($this->tablePath . DIRECTORY_SEPARATOR . $id . '.txt'));

            return $this->setAttributes($accountInfo);
        }

        throw new \Exception('Model Not Found', 404);
    }

    public function all(): array
    {
        $accountFiles = $this->getAllAccountFiles();

        if (!empty($accountFiles)) {
            $accounts = array_map(function ($file) {
                return unserialize(file_get_contents($this->tablePath . DIRECTORY_SEPARATOR . $file));
            }, $accountFiles);

            return array_map(function ($account) {
                return new self($account);
            }, $accounts);
        }

        return [];
    }

    /**
     * @param  array  $attributes
     * @return $this
     * @throws \Exception
     */
    public function create(array $attributes): ModelFile
    {
        $this->checkFillable($attributes);

        $fileName = $this->tablePath . DIRECTORY_SEPARATOR . $attributes['id'] . '.txt';

        if (!file_exists($fileName)) {
            fopen($fileName, 'w');

            file_put_contents($fileName, serialize($attributes));
        }

        return $this->setAttributes($attributes);
    }

    /**
     * @param  array  $attributes
     * @param  string  $needle
     * @return ModelFile|int
     * @throws \Exception
     */
    public function update(array $attributes, string $needle): ModelFile|int
    {
        $this->checkFillable($attributes);
        $model = $this->find($attributes[$needle]);

        if (!empty($model->getAttributes())) {
            file_put_contents(
                $this->tablePath . DIRECTORY_SEPARATOR . $attributes[$needle] . '.txt',
                serialize($attributes),

            );

            return $this->setAttributes($attributes);
        }

        return 0;
    }

    public function firstOrCreate(array $attributes)
    {
        // TODO: Implement firstOrCreate() method.
    }

    public function reset(array $attributes)
    {
        $accountFiles = $this->getAllAccountFiles();
        $initialFile = $this->tablePath . DIRECTORY_SEPARATOR . $attributes['id'] . '.txt';

        foreach ($accountFiles as $accountFile) {
            unlink($this->tablePath . DIRECTORY_SEPARATOR . $accountFile);
        }

        fopen($initialFile, 'w+');
        file_put_contents($initialFile, serialize($attributes));
    }

    private function getAllAccountFiles(): array
    {
        return array_values(array_filter(scandir($this->tablePath), function ($file) {
            return '.' !== $file && '..' !== $file;
        }));
    }
}