<?php

namespace App\traits\crm;

use com\zoho\api\authenticator\store\FileStore;
use com\zoho\api\logger\Levels;
use com\zoho\api\logger\LogBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\UserSignature;
use Illuminate\Support\Facades\Storage;

trait hasCRMConfig
{
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
        $tokenstore = new FileStore(Storage::disk('local')->path('auth/php_sdk_token.txt'));

    }
}
