<?php

namespace Ebanx\Libs;

use JetBrains\PhpStorm\Pure;

final class Router
{
    const RESTAPI_METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE'
    ];

    private string $namespace = "Ebanx\\Controller\\";

    public function namespace(string $namespace): Router
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param  array  $routes
     * @return Router
     * @throws \Exception
     */
    public function route(array $routes): Router
    {
        $requestUri = explode('?', trim($_SERVER['REQUEST_URI'], '/'))[0];

        $routeKeys = array_map(function ($route) {
            return trim($route, '/');
        }, array_keys($routes));

        if (!in_array($requestUri, $routeKeys)) {
            header(Config::API_HTTP_CODE_400);
            throw new \Exception('Bad Request', 400);
        }

        $formattedRoutes = array_combine($routeKeys, array_values($routes));

        // Map the corresponded route
        $routePair = $formattedRoutes[$requestUri];

        // Check request method
        list($method, $action) = $routePair;

        if (!$this->checkHttpMethod($method)) {
            header(Config::API_HTTP_CODE_405);
            throw new \Exception('Method Not Allowed', 405);
        }

        $this->parseRequestParams($requestUri, $action);

        return $this;
    }

    /**
     * Parse the given arguments and pass the request to the dedicated controller to handle
     *
     * @param ...$params
     * @return mixed|void
     * @throws \Exception
     */
    private function parseRequestParams(...$params)
    {
        list($uri, $action) = $params;

        if (gettype($action) === 'string' && $action !== '' && str_contains($action, '@')) {
            $classNAction = explode('@', $action);
            $class = $this->namespace . $classNAction[0];

            if (!class_exists($class)) {
                header(Config::API_HTTP_CODE_404);
                throw new \Exception("$class Not Found", 404);
            }

            if (!method_exists($class, $classNAction[1])) {
                header(Config::API_HTTP_CODE_404);
                throw new \Exception("$class >> {$classNAction[1]} Not Found", 404);
            }

            // Pass id contained in the uri to the action
            $parsedUriId = $this->parseRequestUri($uri);

            return (new $class)->{$classNAction[1]}($parsedUriId);
        }

//        header(Config::API_HTTP_CODE_400);
//        throw new \Exception('Bad Request', 400);
    }

    /**
     * @param  string  $uri
     * @return string|null
     * @throws \Exception
     */
    private function parseRequestUri(string $uri): ?string
    {
        $uriWithQuery = trim($uri, '/');
        $uriArray = explode('?', $uriWithQuery);
        $uriArrayWithId = explode('/', $uriArray[0]);

        preg_match("/^([a-z A-Z]+)(?:\/([0-9]+))?$/", $uriArray[0], $m);

        if (!count($m)) {
            header(Config::API_HTTP_CODE_400);
            throw new \Exception('Invalid Uri', 400);
        }


        return count($uriArrayWithId) > 1 ? $uriArrayWithId[count($uriArrayWithId) - 1] : null;
    }

    /**
     * Check if the given method is an available REST method
     *
     * @param  string  $method
     * @return bool
     * @throws \Exception
     */
    private function checkHttpMethod(string $method): bool
    {
        return $method
            && in_array(strtoupper($method), self::RESTAPI_METHODS)
            && $_SERVER['REQUEST_METHOD'] === strtoupper($method);
    }
}