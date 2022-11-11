<?php

namespace App\traits\migration;

use Illuminate\Support\Facades\Storage;

class MigrationFieldMapping
{
    protected string $module;

    public function __construct($module)
{
    $this->module = $module;
}

public function check(): \Exception|static
{
    if(!Storage::disk('local')->exists("migration/{$this->module}/field-mapping.json"))
    {
        return new \Exception("Field Mapping is missing for {$this->module}");
    }
    return $this;
}

public function getFieldMapping(): ?array
{
    return json_decode(Storage::disk('local')->get("migration/{$this->module}/field-mapping.json"), true);
}

}
