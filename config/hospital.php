<?php

/*
|--------------------------------------------------------------------------
| Hospital details
|--------------------------------------------------------------------------
| Shown on printed invoices/receipts and on the website contact areas.
| Set the real values in .env (HOSPITAL_*).
*/

return [
    'name' => env('HOSPITAL_NAME', env('APP_NAME', 'Hospital Management System')),
    'email' => env('HOSPITAL_EMAIL', 'info@hospital.com'),
    'phone' => env('HOSPITAL_PHONE', ''),
    'address' => env('HOSPITAL_ADDRESS', ''),
    'tax_number' => env('HOSPITAL_TAX_NUMBER', ''),
    // Path under public/
    // Shown after money amounts on dashboards, e.g. "₪", "JOD", "USD". Empty = no symbol.
    'currency' => env('HOSPITAL_CURRENCY', ''),
    'logo' => env('HOSPITAL_LOGO', 'Dashboard/img/brand/logo.png'),
];
