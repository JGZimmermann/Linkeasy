<?php

namespace Config;

class App
{
    public static array $middlewareAliases = [
        'auth' => \App\Middleware\Authenticate::class,
        'role' => \App\Middleware\AuthenticateUserRole::class,
        'permission' => \App\Middleware\AuthenticateUserEditDelete::class,
    ];
}
