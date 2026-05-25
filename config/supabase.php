<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supabase Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Supabase Storage (S3 Compatible API)
    | Database menggunakan Railway PostgreSQL
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    */

    'storage' => [
        'endpoint' => env('AWS_ENDPOINT', 'https://twbvqgjedeapqszljzox.supabase.co/storage/v1/s3'),
        'url' => env('AWS_URL', 'https://twbvqgjedeapqszljzox.supabase.co/storage/v1/object/public'),
        'bucket' => env('AWS_BUCKET', 'produk'),
        'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
        'key' => env('AWS_ACCESS_KEY_ID', ''),
        'secret' => env('AWS_SECRET_ACCESS_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Project Information
    |--------------------------------------------------------------------------
    */

    'project_ref' => 'twbvqgjedeapqszljzox',
    'project_url' => 'https://twbvqgjedeapqszljzox.supabase.co',
    'url' => env('SUPABASE_URL', 'https://twbvqgjedeapqszljzox.supabase.co'),
    'key' => env('SUPABASE_KEY', ''),
    'service_key' => env('SUPABASE_SERVICE_KEY', ''),

];
