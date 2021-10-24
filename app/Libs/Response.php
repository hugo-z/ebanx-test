<?php

namespace Ebanx\Libs;

class Response
{
    public static function json($data, int $code): void
    {
        header(Config::matchHeaderWithCode($code));

        if (is_string($data)) {
            echo $data;
            return;
        }
        echo json_encode($data, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES);
    }
}