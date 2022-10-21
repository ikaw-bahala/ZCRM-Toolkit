<?php

namespace App\traits\migration;

use App\traits\crm\hasCRMConfig;
use League\Csv\ByteSequence;
use League\Csv\Reader;

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




    }

}
