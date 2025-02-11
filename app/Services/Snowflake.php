<?php

namespace App\Services;

class Snowflake
{
    private int $epoch = 1704067200000; // Custom epoch: 2024-01-01 (milliseconds)
    private int $machineId;
    private int $sequence = 0;
    private int $lastTimestamp = -1;

    public function __construct(int $machineId = 1)
    {
        if ($machineId < 0 || $machineId > 1023) {
            throw new \Exception("Machine ID must be between 0 and 1023");
        }
        $this->machineId = $machineId;
    }

    public function generateId(): int
    {
        $timestamp = $this->currentTimeMillis();

        if ($timestamp === $this->lastTimestamp) {
            $this->sequence = ($this->sequence + 1) & 4095; // 12-bit sequence (0-4095)
            if ($this->sequence === 0) {
                while ($timestamp <= $this->lastTimestamp) {
                    $timestamp = $this->currentTimeMillis(); // Wait for the next millisecond
                }
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $timestamp;

        // Construct the 64-bit Snowflake ID
        return (($timestamp - $this->epoch) << 22) | ($this->machineId << 12) | $this->sequence;
    }

    private function currentTimeMillis(): int
    {
        return (int) floor(microtime(true) * 1000);
    }
}
