<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Your Salesforce credentials
    |--------------------------------------------------------------------------
    |
    |
    */

    // production
    'username' => env('sales@microsystem.com.eg'),
    'password' => env('1403636mra'),
    'token' => env('Ls4fh6kdwvJFHGohbPl4HFGO2'),
    'wsdl' => storage_path('app/' . env('SALESFORCE_WSDL', '/enterprise.wsdl.xml'))
];
