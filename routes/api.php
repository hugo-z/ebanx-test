<?php

use Ebanx\Libs\Router;

$Router = new Router();

$namespace = 'Ebanx\\Controller\\';

try {
    $Router->route(
        [
            '/balance' => ['get', 'AccountController@getBalance'],
            '/getAllAccounts' => ['get', 'AccountController@getAllAccounts'],
            '/event' => ['post', 'AccountController@eventAction'],
            '/reset' => ['post', 'AccountController@reset'],
        ]
    )->namespace($namespace);
} catch (Exception $e) {
//    dump($e);
}