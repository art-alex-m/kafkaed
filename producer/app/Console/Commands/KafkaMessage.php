<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Junges\Kafka\Producers\MessageBatch;

/**
 * Class KafkaMessage.
 *
 * @package App\Console\Commands
 */
class KafkaMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kamsg:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message to Kafka broker';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $producer = Kafka::publishOn('kafkaed-1');

        while (true) {
            $num = 0;
            $limit = 500;
            $batch = new MessageBatch();
            while (++$num <= $limit) {
                $batch->push($this->createMessage($num));
            }
            $producer->sendBatch($batch);
            usleep(6500);
        }
    }

    /**
     * @param int $number
     *
     * @return Message
     */
    protected function createMessage(int $number): Message
    {
        $id = Str::uuid();

        return new Message(
            body: [
                'id' => $id,
                'number' => $number,
                'message' => 'Test message',
                'created' => microtime(true),
            ],
            key: $id
        );
    }
}
