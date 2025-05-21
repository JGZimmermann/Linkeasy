<?php

namespace Config;

use Lib\FlashMessage;

class App
{
    public static array $middlewareAliases = [
        'auth' => \App\Middleware\Authenticate::class,
        'role' => \App\Middleware\AuthenticateUserRole::class
    ];
}
