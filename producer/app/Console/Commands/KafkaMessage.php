<?php

namespace App\Console\Commands;

use App\Models\KafkaConfig;
use App\Models\Transmission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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
    protected $signature = 'kamsg:send '
    . '{--s|speed=1 : Producer messages speed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message to Kafka broker';

    protected int $updSpeedTimeout = 1;
    protected string $kafkaed1Topic = 'kafkaed-1';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $producer = Kafka::publishOn($this->kafkaed1Topic);
        $speed = Transmission::from($this->option('speed'));
        $check = $speed->microtime();
        $updSpeed = $this->updateSpeedTime();

        while (true) {
            $current = microtime(true);

            if ($current > $updSpeed) {
                $speed = $this->updateSpeed();
                $updSpeed = $this->updateSpeedTime();
            }

            if ($speed->timeout() < 0) {
                usleep(500000);
                continue;
            }

            if ($current < $check) {
                usleep(300);
                continue;
            }

            $num = 0;
            $limit = 500;
            $batch = new MessageBatch();
            while (++$num <= $limit) {
                $batch->push($this->createMessage($num, $speed->value));
            }
            $producer->sendBatch($batch);

            $check = $speed->microtime();
        }
    }

    /**
     * Create message
     *
     *
     * @param int $number
     * @param int $transmission
     *
     * @return Message
     */
    protected function createMessage(int $number, int $transmission): Message
    {
        $id = Str::uuid();

        return new Message(
            body: [
                'id' => $id,
                'number' => $number,
                'message' => 'Test message',
                'created' => microtime(true),
                'transmission' => $transmission,
            ],
            key: $id
        );
    }

    /**
     * Next time for speed update.
     *
     * @return float
     */
    protected function updateSpeedTime(): float
    {
        return microtime(true) + $this->updSpeedTimeout;
    }

    /**
     * Update transmission.
     *
     * @return Transmission
     */
    protected function updateSpeed(): Transmission
    {
        $speedValue = Cache::get(KafkaConfig::KAFKAED_1_CONFIG, $this->option('speed'));
        $speed = Transmission::tryFrom($speedValue);
        if (null === $speed) {
            $speed = Transmission::SPEED_0;
        }

        return $speed;
    }
}
