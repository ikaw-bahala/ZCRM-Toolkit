<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CRMDelete extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'crm:delete';

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
     */
    public function handle()
    {
        $module = $this->anticipate('What module you want to perform this action? ', ['Leads', 'Contacts', 'Accounts', 'Deals', 'Products']);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
