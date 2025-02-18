<?php

use App\Services\Snowflake;

function generate_snowflake_id(int $machineId = null): int
{
    static $snowflake;

    // Initialize the Snowflake instance if it's not already created
    if (!$snowflake) {
        $machineId = $machineId ?? env('SNOWFLAKE_MACHINE_ID', 1);  // Default machine ID from environment
        $snowflake = new Snowflake($machineId);
    }

    return $snowflake->generateId();
}

