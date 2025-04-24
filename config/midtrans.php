<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for Midtrans payment gateway.
    | You can find your API key and other credentials in your Midtrans account.
    |
    */

  'server_key' => env('MIDTRANS_SERVER_KEY'),
  'is_production' => env('MIDTRANS_IS_PRODUCTION'),
  'isSanitized' => env('MIDTRANS_IS_SANITIZED'),
  'is3ds' => env('MIDTRANS_IS_3DS'),
];
