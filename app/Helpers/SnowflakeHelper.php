<?php

use App\Services\Snowflake;

if (!function_exists('generate_snowflake_id')) {

    /** Generate a unique Snowflake ID. @return int */
    
    function generate_snowflake_id(): int
    {
        $machineId = env('SNOWFLAKE_MACHINE_ID', 1);                // Fetch machine ID from .env
        $snowflake = new Snowflake($machineId);                     // Initialize Snowflake generator
        return $snowflake->generateId();                            // Generate and return Snowflake ID
    }
}
