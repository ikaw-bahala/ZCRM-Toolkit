<?php

namespace App\traits\migration;

use App\Helper\CRMSDK;
use App\traits\crm\hasCRMConfig;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

trait Migrate
{
    use hasCSVFile;
    use hasCRMConfig;

    private array $data;

    /**
     * @throws \Exception
     */
    public function startMigration($self, $module): void
    {
        $this->ZCRMconfig();

        $path = $this->getCSV($module);
        $getFieldMappingData = collect((new MigrationFieldMapping($module))->check()->getFieldMapping());
        $getFieldMappingData->each(function($properties) use ($path) {
            $fieldMapping = collect($properties);
            $rows = SimpleExcelReader::create($path)->getRows();
            $rows->each(function (array $csvProperties) use ($fieldMapping){


            });

            dump($fieldMapping->values());
            dump($fieldMapping->keys());

        });
        dump($getFieldMappingData->count());

//        dump((new CRMSDK())->getModuleRecordCount('Leads'));


    }

}
