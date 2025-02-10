<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use App\Logging\MongoDBLogger;

class MongoDBLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->extend('log', function ($log, $app) {
            $log->pushHandler(new MongoDBLogger());
            return $log;
        });
    }
}
