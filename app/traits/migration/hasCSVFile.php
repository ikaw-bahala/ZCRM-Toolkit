<?php

namespace App\traits\migration;

use Illuminate\Support\Facades\Storage;


trait hasCSVFile
{
    protected function checkDirectory($module): void
    {
        if(!Storage::exists("migration/{$module}"))
        {
            Storage::makeDirectory("migration/{$module}");
        }
    }
    protected function CSVPath($module)
    {
        if($module === 'Leads') return Storage::disk('local')->get('migration/Leads/Leads.csv');
        if($module === 'Contacts') return Storage::disk('local')->get('migration/Contacts/Contacts.csv');
        if($module === 'Accounts') return Storage::disk('local')->get('migration/Accounts/Accounts.csv');
        if($module === 'Deals') return Storage::disk('local')->get('migration/Deals/Deals.csv');
        if($module === 'Products') return Storage::disk('local')->get('migration/Products/Products.csv');
    }

    /**
     * @throws \Exception
     */
    public function getCSV($module)
    {
       $this->checkDirectory($module);
       if (!$this->checkLeadCSV($module))
       {
           $this->error("Please move your Lead CSV file to this path storage > migration > {$module} and name it as {$module}.csv");
           return exit();
       }
       return $this->CSVPath($module);

    }

    protected function checkLeadCSV($module): bool
    {
        $this->checkDirectory('Leads');
        return Storage::disk('local')->exists("migration/{$module}/{$module}.csv");
    }

}
