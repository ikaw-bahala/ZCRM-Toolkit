<?php

namespace App\Commands;

use App\traits\hasYesNoQuestion;
use App\traits\migration\hasCSVFile;
use App\traits\migration\hasMigrationMenu;
use App\traits\migration\Migrate;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CRM extends Command
{
    use hasMigrationMenu;
    use hasCSVFile;
    use Migrate;
    use hasYesNoQuestion;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'crm:import';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Allow you to import/migrate bulk record in csv file to your Zoho CRM.';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $selected_module = $this->MigrationMenu($this);
        if($selected_module === null) exit(1);

        $csvfile = $this->getCSV('Leads');
        // Ask confirmation to proceed
        $proceed = $this->areYouSure($this, 'Continue and start migration. proceed?');

        // start migration
        $this->startMigration('Leads');

        $this->info("You have chosen the text option: {$selected_module}");
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
