<?php

namespace App\traits;

trait hasYesNoQuestion
{
    public function areYouSure($command, $message)
    {
        return $command->menu("{$message}")
            ->addOption('yes', 'Yes!')
            ->setForegroundColour('green')
            ->setBackgroundColour('black')
            ->setWidth(200)
            ->setPadding(10)
            ->setMargin(5)
            ->setExitButtonText("Abort")
            ->open();
    }

}
