<?php

namespace App\traits\migration;

use App\Helper\CRMSDK;
use App\traits\crm\hasCRMConfig;
use com\zoho\crm\api\record\BodyWrapper;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\record\RecordOperations;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelReader;

trait Migrate
{
    use hasCSVFile;
    use hasCRMConfig;

    /**
     * @throws \Exception
     */
    public function startMigration($self, $module): void
    {
        $this->ZCRMconfig();
        //Get instance of RecordOperations Class that takes moduleAPIName as parameter
        $recordOperations = new RecordOperations();

        //Get instance of BodyWrapper Class that will contain the request body
        $bodyWrapper = new BodyWrapper();
        //List of Record instances
        $records = new Collection();

        //Get instance of Record Class
        $record1 = new Record();

        $path = $this->getCSV($module);
        $getFieldMappingData = collect((new MigrationFieldMapping($module))->check()->getFieldMapping());
        $rows = SimpleExcelReader::create($path)->getRows();
        $rows->each(function (array $csvProperties) use ($records, $record1, $getFieldMappingData){
            $getFieldMappingData->each(function($properties) use ($records, $csvProperties, $record1) {
                $fieldMapping = collect($properties);
                dump("{$fieldMapping->get('crm_field_name')}", "{$csvProperties[$fieldMapping->get('csv_column')]}");
                $record1->addKeyValue("{$fieldMapping->get('crm_field_name')}", "{$csvProperties[$fieldMapping->get('csv_column')]}");
               $records->push($record1);
                });
            });
//        $getFieldMappingData->each(function($properties) use ($path) {
//            $fieldMapping = collect($properties);
//            $rows = SimpleExcelReader::create($path)->getRows();
//            $rows->each(function (array $csvProperties) use ($fieldMapping){
//                if($csvProperties[$fieldMapping->value('csv_column')] !== null)
//                {
//                    $record1->addKeyValue($fieldMapping->value('crm_field_name'), $csvProperties[$fieldMapping->value('csv_column')]);
//                }
//            });
//        });
        Log::info("test",$records->all());
        dump($records->all());

//        dump((new CRMSDK())->getModuleRecordCount('Leads'));


    }

}
