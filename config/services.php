<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'recaptcha' => [
        'site_key' => env('GOOGLE_RECAPTCHA_KEY'),
        'secret_key' => env('GOOGLE_RECAPTCHA_SECRET'),
        // Google official test keys for local/testing.
        'local_site_key' => env('GOOGLE_RECAPTCHA_LOCAL_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'),
        'local_secret_key' => env('GOOGLE_RECAPTCHA_LOCAL_SECRET', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'),
    ],

    'viettel_einvoice' => [
        'enabled' => env('VIETTEL_EINVOICE_ENABLED', false),
        'base_url' => env('VIETTEL_EINVOICE_BASE_URL', 'https://sinvoice.viettel.vn'),
        'api_url' => env('VIETTEL_EINVOICE_API_URL', ''),
        'publish_sale_path' => env('VIETTEL_EINVOICE_PUBLISH_SALE_PATH', '/invoices/publish'),
        'username' => env('VIETTEL_EINVOICE_USERNAME'),
        'password' => env('VIETTEL_EINVOICE_PASSWORD'),
        'token' => env('VIETTEL_EINVOICE_TOKEN'),
        'client_id' => env('VIETTEL_EINVOICE_CLIENT_ID'),
        'client_secret' => env('VIETTEL_EINVOICE_CLIENT_SECRET'),
        'company_code' => env('VIETTEL_EINVOICE_COMPANY_CODE'),
        'branch_code' => env('VIETTEL_EINVOICE_BRANCH_CODE'),
        'template_code' => env('VIETTEL_EINVOICE_TEMPLATE_CODE'),
        'invoice_series' => env('VIETTEL_EINVOICE_INVOICE_SERIES'),
        'invoice_type' => env('VIETTEL_EINVOICE_INVOICE_TYPE', '01GTKT'),
        'default_payment_method' => env('VIETTEL_EINVOICE_PAYMENT_METHOD', 'TM/CK'),
        'publish_on_sale' => env('VIETTEL_EINVOICE_PUBLISH_ON_SALE', false),
        'sync_mode' => env('VIETTEL_EINVOICE_SYNC_MODE', 'sync'),
        'verify_ssl' => env('VIETTEL_EINVOICE_VERIFY_SSL', true),
        'timeout' => (int) env('VIETTEL_EINVOICE_TIMEOUT', 30),
    ],

];
