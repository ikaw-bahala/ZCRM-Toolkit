<?php

namespace App\traits\migration;

use Spatie\SimpleExcel\SimpleExcelReader;

trait Migrate
{
    use hasCSVFile;

    private array $data;

    /**
     * @throws \Exception
     */
    public function startMigration($self, $module): void
    {
        $path = $this->getCSV($module);
        // $rows is an instance of Illuminate\Support\LazyCollection
        $rows = SimpleExcelReader::create($path,  'csv')
            ->headersToSnakeCase()
            ->trimHeaderRow()
            ->getRows();
        $rows->each(function(array $rowProperties) {
            dump($rowProperties);
            // in the first pass $rowProperties will contain
            // ['email' => 'john@example.com', 'first_name' => 'john']
        });

    }

}
