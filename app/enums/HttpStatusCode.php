<?php

namespace App\enums;

enum HttpStatusCode
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NO_CONTENT = 204;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const INTERNAL_SERVER_ERROR = 500;
    const SERVICE_UNAVAILABLE = 503;
    const VALIDATION_ERROR = 309;
    const TOO_MANY_ATTEMPTS = 429;
    const CONFLICT = 409;
    const TWO_FA_REQUIRED = 428;


    public static function getDescription($value): string
    {
        switch ($value) {
            case self::OK:
                return 'OK';
            case self::CREATED:
                return 'Created';
            case self::ACCEPTED:
                return 'Accepted';
            case self::NO_CONTENT:
                return 'No Content';
            case self::BAD_REQUEST:
                return 'Bad Request';
            case self::UNAUTHORIZED:
                return 'Unauthorized';
            case self::FORBIDDEN:
                return 'Forbidden';
            case self::NOT_FOUND:
                return 'Not Found';
            case self::INTERNAL_SERVER_ERROR:
                return 'Internal Server Error';
            case self::SERVICE_UNAVAILABLE:
                return 'Service Unavailable';
            case self::VALIDATION_ERROR:
                return 'Validation Error';
            case self::TOO_MANY_ATTEMPTS:
                return 'Too many attempts';
            case self::CONFLICT:
                return 'Conflict';
            case self::TWO_FA_REQUIRED:
                    return 'Two factor authentication is required';
            default:
                return self::getDescription($value);
        }
    }
}

