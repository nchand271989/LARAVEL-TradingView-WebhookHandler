<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RedisConnectionTest extends TestCase
{
    /**
     * Test Redis connection and store/retrieve a key.
     *
     * @return void
     */
    public function test_redis_connection()
    {
        try {
            // Check Redis connection
            $this->assertNotNull(Redis::ping(), 'Redis connection failed!');

            // Store a test key in Redis
            Cache::put('test_key', 'Redis is working!', 10);

            // Retrieve the value from Redis
            $value = Cache::get('test_key');

            // Assert that the value is correct
            $this->assertEquals('Redis is working!', $value, 'Redis value retrieval failed!');

        } catch (\Exception $e) {
            $this->fail('Redis connection test failed: ' . $e->getMessage());
        }
    }
}
