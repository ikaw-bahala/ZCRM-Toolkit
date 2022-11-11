<?php

use com\zoho\crm\api\dc\USDataCenter;
use Illuminate\Support\Facades\Storage;

return [
    "log_path" => 'zcrm-php_sdk_log.log',
    "user_signature" => env('CRM_USER_SIGNATURE', null),
    "token_path" => 'auth/php_sdk_token.txt',
    /*
    * Configure the environment
    * which is of the pattern Domain::Environment
    * Available Domains: USDataCenter, EUDataCenter, INDataCenter, CNDataCenter, AUDataCenter
    * Available Environments: PRODUCTION(), DEVELOPER(), SANDBOX()
    */
    "environment" =>  USDataCenter::PRODUCTION(),
    /*
   * Create a Token instance that requires the following
   * clientId -> OAuth client id.
   * clientSecret -> OAuth client secret.
   * grantToken -> GRANT token.
   * redirectURL -> OAuth redirect URL.
   */
    "clientId" => env('CRM_CLIENT_ID', null),
    "clientSecret" => env('CRM_CLIENT_SECRET', null),
    "grantToken" => env('CRM_GRANT_TOKEN', null),
    "redirectURL" => env('CRM_REDIRECT_URL'),

];
