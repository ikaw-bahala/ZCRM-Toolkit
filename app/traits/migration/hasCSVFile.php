<?php

namespace App\traits\migration;

use Illuminate\Support\Facades\Storage;


trait hasCSVFile
{
    private function checkDirectory($module): void
    {
        if(!Storage::exists("migration/{$module}"))
        {
            Storage::makeDirectory("migration/{$module}");
        }
    }
    private function CSVPath($module)
    {
        if($module === "$module") return Storage::disk('local')->path("migration/{$module}/{$module}.csv");
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
        return Storage::disk('local')->exists("migration/{$module}/{$module}.csv");
    }

}
