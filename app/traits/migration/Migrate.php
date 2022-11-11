<?php

namespace App\traits\migration;

use App\Helper\CRMSDK;
use App\traits\crm\hasCRMConfig;
use Illuminate\Support\Facades\Storage;

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
        $getFieldMappingData = (new MigrationFieldMapping($module))->check()->getFieldMapping();
        dump($getFieldMappingData);

//        dump((new CRMSDK())->getModuleRecordCount('Leads'));


    }

}
