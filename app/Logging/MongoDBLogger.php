<?php

namespace App\Logging;

use MongoDB\Client;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

use Illuminate\Support\Facades\Auth;

class MongoDBLogger extends AbstractProcessingHandler
{
    protected $collection;

    public function __construct()
    {
        parent::__construct();

        $client = new Client(env('MONGODB_URI', 'mongodb://127.0.0.1:27017'));
        $database = env('MONGODB_DATABASE', 'logs_db');
        $collection = env('MONGODB_COLLECTION', 'logs');

        $this->collection = $client->$database->$collection;
    }

    /** Writes log records to MongoDB. */
    protected function write(LogRecord $record): void
    {
        try {
            $this->collection->insertOne([
                'user_id' => (string) (Auth::id() ?? ''), 
                'message' => $record->message,
                'channel' => $record->channel,
                'level' => $record->level->name,
                'context' => (array) $record->context,
                'extra' => (array) $record->extra,
                'datetime' => $record->datetime->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            // You could log the error here or send it to a fallback logging system
            \Log::error('MongoDB Logger Error: ' . $e->getMessage());
        }
    }

}
