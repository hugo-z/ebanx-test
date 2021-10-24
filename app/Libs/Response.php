<?php

namespace Ebanx\Libs;

use JetBrains\PhpStorm\NoReturn;

class Response
{
    #[NoReturn]
    public static function json($data, int $code): void
    {
        header(Config::matchHeaderWithCode($code));

        if (is_string($data)) {
            exit($data);
        }
        exit(json_encode($data, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES));
    }
}