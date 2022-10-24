<?php

namespace App\Helper;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\record\DeleteRecordParam;
use com\zoho\crm\api\record\ResponseWrapper;


class CRMSDK
{
    /**
     * Get Records
     * This method is used to get all the records of a module and print the response.
     * @param string $moduleAPIName
     * @return array|void
     */
    public function getAllRecord(string $moduleAPIName)
    {

        //Get instance of RecordOperations Class that takes moduleAPIName as parameter
        $recordOperations = new RecordOperations();
        //Call getRecords method
        $response = $recordOperations->getRecords($moduleAPIName);
        if($response !== null) {
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if (in_array($response->getStatusCode(), array(204, 304))) {
               return [
                   "code"=> $response->getStatusCode(),
                    "msg" => $response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n"
                ];
            }
            if($response->isExpected())
            {
                //Get the object from response
                $responseHandler = $response->getObject();

                if($responseHandler instanceof ResponseWrapper) {
                    //Get the received ResponseWrapper instance
                    $responseWrapper = $responseHandler;

                    //Get the obtained Record instances
                    $records = $responseWrapper->getData();
                    return [
                      "code" => 200,
                      "data" => $records
                    ];
                }else if($responseHandler instanceof APIException)
                {
                    //Get the received APIException instance
                    $exception = $responseHandler;

                    //Get the Status
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");

                    //Get the Code
                    echo("Code: " . $exception->getCode()->getValue() . "\n");

                    if($exception->getDetails() != null)
                    {
                        echo("Details: " );

                        //Get the details map
                        foreach($exception->getDetails() as $key => $value)
                        {
                            //Get each value in the map
                            echo($key . " : " . $value . "\n");
                        }
                    }
                    //Get the Message
                    echo("Message: " . $exception->getMessage()->getValue() . "\n");
                }
            }
        }

    }

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
        $paramInstance->add(DeleteRecordParam::wfTrigger(), $wfTrigger);

        //Call deleteRecord method that takes paramInstance, ModuleAPIName and recordId as parameter.
        $response = $recordOperations->deleteRecord($recordId, $moduleAPIName, $paramInstance);
        if ($response !== null) {
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
        }else{
            return [
                'msg' => $response
            ];
        }
    }
}
