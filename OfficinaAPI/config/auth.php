<?php

return [

    'api' => [
    'driver' => 'sanctum',
    'provider' => 'usuarios',
],

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web', // Guard padrão (pode ser 'web' ou 'api')
        'passwords' => 'usuarios', // Provider de senha padrão
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session', // Usa sessões para autenticação
            'provider' => 'usuarios', // Define o provider como 'usuarios'
        ],

        'api' => [
            'driver' => 'session', // Ou 'token' se estiver usando autenticação por token
            'provider' => 'usuarios', // Define o provider como 'usuarios'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'usuarios' => [ // Nome do provider (deve corresponder ao usado nos guards)
            'driver' => 'eloquent', // Usa o Eloquent ORM
            'model' => App\Models\Usuario::class, // Modelo que representa a tabela 'usuarios'
        ],

        // 'users' => [ // Provider padrão (comentado ou removido)
        //     'driver' => 'eloquent',
        //     'model' => App\Models\User::class,
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'usuarios' => [ // Nome do provider de senha (deve corresponder ao provider de usuários)
            'provider' => 'usuarios', // Define o provider de usuários
            'table' => 'password_reset_tokens', // Tabela para tokens de redefinição de senha
            'expire' => 60, // Tempo de expiração do token em minutos
            'throttle' => 60, // Tempo de espera antes de gerar um novo token
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800, // Tempo de expiração da confirmação de senha em segundos

        
];