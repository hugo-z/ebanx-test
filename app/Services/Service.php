<?php

namespace Ebanx\Services;

class Service
{
    /**
     * Validate if requested query keys are valid
     *
     * @param  array  $requiredKeys
     * @param  array  $requestedKeys
     * @return bool
     * @throws \Exception
     */
    protected function validateQueries(array $requiredKeys, array $requestedKeys): bool
    {
        $unProvidedKey = array_diff(
            $requiredKeys,
            $requestedKeys
        );

        if (!empty($unProvidedKey)) {
            throw new \Exception("The required " . implode(',', $unProvidedKey) . ' are not provided', 422);
        }

        return true;
    }
}