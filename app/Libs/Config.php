<?php

namespace Ebanx\Libs;

class Config
{
    const API_CONTENT_TYPE="Content-Type: application/json",
        API_HTTP_CODE_200="HTTP/1.1 200 OK",
        API_HTTP_CODE_201="HTTP/1.1 201 OK",
        API_HTTP_CODE_400="HTTP/1.1 400 Bad Request",
        API_HTTP_CODE_404="HTTP/1.1 404 Not Found",
        API_HTTP_CODE_422="HTTP/1.1 422 Unprocessable Entity",
        API_HTTP_CODE_500="HTTP/1.1 500 Internal Server Error",
        API_HTTP_CODE_405="HTTP/1.1 405 Method Not Allowed";

    /**
     * @param $code
     * @return string
     */
    public static function matchHeaderWithCode($code): string
    {
        return match ($code) {
            201 => self::API_HTTP_CODE_201,
            400 => self::API_HTTP_CODE_400,
            404 => self::API_HTTP_CODE_404,
            405 => self::API_HTTP_CODE_405,
            422 => self::API_HTTP_CODE_422,
            500 => self::API_HTTP_CODE_500,
            default => self::API_HTTP_CODE_200,
        };
    }
}