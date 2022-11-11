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
        if(!Storage::disk('local')->exists(config('crm.log_path')))
        {
            Storage::disk('local')->put(config('crm.log_path'), '');
        }

        $logger = (new LogBuilder())
            ->Level(Levels::INFO)
            ->filePath(Storage::disk('local')->path(config('crm.log_path')))
            ->build();

        $user = new UserSignature(config('crm.user_signature'));
        /*
        * Configure the environment
        * which is of the pattern Domain::Environment
        * Available Domains: USDataCenter, EUDataCenter, INDataCenter, CNDataCenter, AUDataCenter
        * Available Environments: PRODUCTION(), DEVELOPER(), SANDBOX()
        */
        $environment = config('crm.environment');

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
            ->clientId(config('crm.clientId'))
            ->clientSecret(config('crm.clientSecret'))
            ->grantToken(config('crm.grantToken'))
            ->redirectURL(config('crm.redirectURL'))
            ->build();
        /*
         * TokenStore can be any of the following
         * DB Persistence - Create an instance of DBStore
         * File Persistence - Create an instance of FileStore
         * Custom Persistence - Create an instance of CustomStore
        */
        // check if existing or not, then create if not existing

        if(!Storage::disk('local')->exists(config('crm.token_path')))
        {
           Storage::disk('local')->put(config('crm.token_path'), '');
        }

        $tokenstore = new FileStore(Storage::disk('local')->path(config('crm.token_path')));

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
