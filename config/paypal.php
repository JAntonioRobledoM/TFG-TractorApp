<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de PayPal
    |--------------------------------------------------------------------------
    |
    | Esta configuración se usa para la integración de PayPal en la aplicación.
    |
    */

    // Credenciales principales
    'client_id' => env('PAYPAL_CLIENT_ID', ''),
    'secret' => env('PAYPAL_SECRET', ''),
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'currency' => env('PAYPAL_CURRENCY', 'EUR'),

    // Cuentas por defecto para sandbox
    'sandbox_default_seller_account' => env('PAYPAL_SANDBOX_DEFAULT_SELLER', 'sb-seller@business.example.com'),
    'sandbox_default_buyer_account' => env('PAYPAL_SANDBOX_DEFAULT_BUYER', 'sb-buyer@personal.example.com'),

    // Mapeo de vendedores a cuentas de PayPal para sandbox
    'seller_sandbox_accounts' => [
        // id_usuario => correo_sandbox
        1 => 'sb-vendedor1@business.example.com',
        2 => 'sb-vendedor2@business.example.com',
    ],
    
    // Mapeo de compradores a cuentas de PayPal para sandbox
    'buyer_sandbox_accounts' => [
        // id_usuario => correo_sandbox
        1 => 'sb-comprador1@personal.example.com',
    ],
    
    // Credenciales para cuentas de sandbox (opcional, útil para pruebas automatizadas)
    'sandbox_credentials' => [
        // Credenciales para compradores
        'sb-comprador1@personal.example.com' => [
            'email' => 'sb-comprador1@personal.example.com',
            'password' => 'EG=4xtsl',
        ],
    ],
];