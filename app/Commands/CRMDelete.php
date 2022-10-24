<?php

namespace App\Commands;

use App\traits\crm\hasCRMConfig;
use com\zoho\crm\api\exception\SDKException;
use App\Helper\CRMSDK;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CRMDelete extends Command
{
    use hasCRMConfig;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'crm:delete
                            {module : possible input Leads, Contacts, Accounts, Deals, Products}
                            {--id=* : module record CRM Ids}
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Allow you to delete record in your Zoho CRM.';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws SDKException
     */
    public function handle()
    {
        $module = $this->argument('module');
        $options = $this->option('id');

        // delete all record if ths --id is empty
        if(empty($options))
        {
            $input = $this->ask("Are you sure you want to delete all record in {$module}", 'Yes');
            if($input === 'Yes' || $input === 'Y' || $input === 'y')
            {
                // delete all record

            }
        }else{
            $this->ZCRMconfig();
            $idsString = implode(',', $options);
            $input = $this->ask("Are you sure you want to delete all these ({$idsString}) record in {$module}", 'Yes');
            if($input === 'Yes' || $input === 'Y' || $input === 'y')
            {
                // delete all record
                $ids = collect($options);
                $this->output->progressStart($ids->count());
                $ids->each(function($value, $key) use ($module,) {
                    $this->output->progressAdvance();
                    $resp = (new CRMSDK)->deleteRecord($module, $value);
                    $resp = collect($resp);
                    if($resp->get('code') == 'SUCCESS')
                    {
                        $this->newLine(2);
                        $this->info($resp->get('msg'));
                    }else{
                        dump($resp);
                    }
                });
                sleep(5);
                $this->output->progressFinish();
                $this->info('Processing Done!');
            }
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
