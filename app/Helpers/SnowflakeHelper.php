<?php

use App\Services\Snowflake;

function generate_snowflake_id(int $machineId = null): int
{

    $machineId = $machineId ?? env('SNOWFLAKE_MACHINE_ID', 1);      /** If no machine ID is passed, use the one from the environment */
    $snowflake = new Snowflake($machineId);
    return $snowflake->generateId();
}

