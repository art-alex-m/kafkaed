<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

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
            foreach ([50, 100, 500, 1000] as $limit) {
                $num = 0;
                while (++$num <= $limit) {
                    $producer->withMessage($this->createMessage($num))->send();
                }
                usleep(100);
            }
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
