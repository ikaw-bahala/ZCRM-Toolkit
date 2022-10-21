<?php

namespace App\traits\migration;

use Illuminate\Support\Facades\Storage;


trait hasCSVFile
{
    private function checkDirectory($module): void
    {
        if(!Storage::exists("storage/migration/{$module}"))
        {
            Storage::makeDirectory("storage/migration/{$module}");
        }
    }
    private function CSVPath($module)
    {
        if($module === "$module") return Storage::disk('local')->path("storage/migration/{$module}/{$module}.csv");
    }

    /**
     * @throws \Exception
     */
    public function getCSV($module)
    {
       $this->checkDirectory("$module");
       if (!$this->checkCSV("$module"))
       {
           $this->error("Please move your Lead CSV file to this path storage > migration > {$module} and name it as {$module}.csv");
           return exit();
       }
       return $this->CSVPath("$module");

    }

    private function checkCSV($module): bool
    {
        return Storage::disk('local')->exists("storage/migration/{$module}/{$module}.csv");
    }

}
