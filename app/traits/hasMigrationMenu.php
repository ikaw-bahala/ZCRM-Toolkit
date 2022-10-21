<?php

namespace App\traits;

trait hasMigrationMenu
{
    public function MigrationMenu($command)
    {
        return $command->menu('Module Selection')
            ->addOption('lead', 'Leads')
            ->addOption('contact', 'Contacts')
            ->addOption('account', 'Accounts')
            ->addOption('deal', 'Deals')
            ->addOption('product', 'Products')
            ->setForegroundColour('green')
            ->setBackgroundColour('black')
            ->setWidth(200)
            ->setPadding(10)
            ->setMargin(5)
            ->setExitButtonText("Abort")
            ->open();
    }

}
