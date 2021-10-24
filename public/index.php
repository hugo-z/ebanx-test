<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


define('PUBLIC_PATH', realpath(dirname(__FILE__)));

define("APP_PATH", realpath(PUBLIC_PATH . '/../app') . DIRECTORY_SEPARATOR);

define("CACHE_PATH", realpath(PUBLIC_PATH . '/../storage/cache') . DIRECTORY_SEPARATOR);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../routes/api.php';
