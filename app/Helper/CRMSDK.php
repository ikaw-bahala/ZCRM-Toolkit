<?php

namespace App\Helper;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\record\DeleteRecordParam;
use com\zoho\crm\api\record\GetRecordHeader;


class CRMSDK
{

    /**
     * Delete Record
     * This method is used to delete a single record of a module with ID and print the response.
     * @param string $moduleAPIName $moduleAPIName - The API Name of the record's module.
     * @param string $recordId $recordId - The ID of the record to be deleted.
     * @param bool $wfTrigger - Trigger workflow rules on the ZCRM module
     * @throws SDKException
     */
    public function deleteRecord(string $moduleAPIName, string $recordId, bool $wfTrigger = false)
    {
        //Get instance of RecordOperations Class
        $recordOperations = new RecordOperations();

        //Get instance of ParameterMap Class
        $paramInstance = new ParameterMap();

        $paramInstance->add(DeleteRecordParam::wfTrigger(), false);

        $headerInstance = new HeaderMap();

        $headerInstance->add(GetRecordHeader::XEXTERNAL(), "Leads.External");

        //Call deleteRecord method that takes paramInstance, ModuleAPIName and recordId as parameter.
        $response = $recordOperations->deleteRecord($recordId, $moduleAPIName, $paramInstance, $headerInstance);
        if ($response != null) {
            //Get the status code from response
            echo("Status Code: " . $response->getStatusCode() . "\n");

            if ($response->isExpected()) {
                //Get object from response
                $actionHandler = $response->getObject();

                if ($actionHandler instanceof ActionWrapper) {
                    //Get the received ActionWrapper instance
                    $actionWrapper = $actionHandler;

                    //Get the list of obtained ActionResponse instances
                    $actionResponses = $actionWrapper->getData();

                    foreach ($actionResponses as $actionResponse) {
                        //Check if the request is successful
                        if ($actionResponse instanceof SuccessResponse) {
                            //Get the received SuccessResponse instance
                            $successResponse = $actionResponse;
                            return [
                                "code" => $successResponse->getCode()->getValue(),
                                'msg' => $successResponse->getMessage()->getValue()
                            ];

                        } //Check if the request returned an exception
                        else if ($actionResponse instanceof APIException) {
                            //Get the received APIException instance
                            $exception = $actionResponse;
                            return [
                                "code" => $exception->getCode()->getValue(),
                                'msg' => $exception->getMessage()->getValue()
                            ];
                        }
                    }
                } //Check if the request returned an exception
                else if ($actionHandler instanceof APIException) {
                    //Get the received APIException instance
                    $exception = $actionHandler;
                    return [
                        "code" => $exception->getCode()->getValue(),
                        'msg' => $exception->getMessage()->getValue()
                    ];
                }
            }
        }
    }

}
