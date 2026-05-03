<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fonnte WhatsApp Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk menghubungkan sistem dengan Fonnte API.
    | Daftar di https://fonnte.com, hubungkan nomor WA Anda,
    | lalu salin token API ke file .env.
    |
    */

    'token' => env('FONNTE_TOKEN', ''),

    'url' => env('FONNTE_URL', 'https://api.fonnte.com/send'),

    'sender' => env('WHATSAPP_SENDER', '+62 811-4701-927'),

    /*
    | Timeout request ke API Fonnte dalam detik
    */
    'timeout' => 30,
];
