<?php

namespace App\traits\crm;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\api\authenticator\store\FileStore;
use com\zoho\api\logger\Levels;
use com\zoho\api\logger\LogBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\SDKConfigBuilder;
use com\zoho\crm\api\UserSignature;
use Illuminate\Support\Facades\Storage;

trait hasCRMConfig
{
    /*
     *  Scope
     *
     *  Aaaserver.profile.Read,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoCRM.users.ALL
     *
     *
     */

    /**
     * @throws SDKException
     */
    public function ZCRMconfig()
    {
        $logger = (new LogBuilder())
            ->Level(Levels::INFO)
            ->filePath(Storage::disk('local')->path('zcrm-php_sdk_log.log'))
            ->build();
        $user = new UserSignature(env('CRM_USER_SIGNATURE', null));
        /*
        * Configure the environment
        * which is of the pattern Domain::Environment
        * Available Domains: USDataCenter, EUDataCenter, INDataCenter, CNDataCenter, AUDataCenter
        * Available Environments: PRODUCTION(), DEVELOPER(), SANDBOX()
        */
        $environment = USDataCenter::PRODUCTION();

        /*
        * Create a Token instance that requires the following
        * clientId -> OAuth client id.
        * clientSecret -> OAuth client secret.
        * refreshToken -> REFRESH token.
        * accessToken -> Access token.
        * grantToken -> GRANT token.
        * id -> User unique id.
        * redirectURL -> OAuth redirect URL.
        */
        //Create a Token instance
        // if refresh token is available
        // The SDK throws an exception, if the given id is invalid.
        $token = (new OAuthBuilder())
            ->clientId(env('CRM_CLIENT_ID', null))
            ->clientSecret(env('CRM_CLIENT_SECRET', null))
            ->grantToken(env('CRM_GRANT_TOKEN', null))
            ->redirectURL(env('CRM_REDIRECT_URL'))
            ->build();
        /*
         * TokenStore can be any of the following
         * DB Persistence - Create an instance of DBStore
         * File Persistence - Create an instance of FileStore
         * Custom Persistence - Create an instance of CustomStore
        */

        $tokenstore = new FileStore(Storage::disk('local')->path('auth/php_sdk_token.txt'));

        $sdkConfig = (new SDKConfigBuilder())
            ->autoRefreshFields(false)
            ->pickListValidation(false)
            ->sslVerification(false)
            ->connectionTimeout(2)
            ->timeout(2)
            ->build();

        /*
           * Set the following in InitializeBuilder
           * user -> UserSignature instance
           * environment -> Environment instance
           * token -> Token instance
           * store -> TokenStore instance
           * SDKConfig -> SDKConfig instance
           * resourcePath -> resourcePath - A String
           * logger -> Log instance (optional)
           * requestProxy -> RequestProxy instance (optional)
         */
        (new InitializeBuilder())
            ->user($user)
            ->environment($environment)
            ->token($token)
            ->store($tokenstore)
            ->SDKConfig($sdkConfig)
            ->resourcePath(getcwd())
            ->logger($logger)
            ->initialize();

    }
}
