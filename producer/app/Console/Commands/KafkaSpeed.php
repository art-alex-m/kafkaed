<?php

namespace App\Console\Commands;

use Artalexm\KafkaedCommon\Messages\Transmission;
use Artalexm\KafkaedCommon\Models\KafkaConfig;
use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

/**
 * Class KafkaSpeed.
 *
 * @package App\Console\Commands
 */
class KafkaSpeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kamsg:speed {speed : Speed of message generation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change speed of message generation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $transmissionMsg = new Transmission(microtime(true), $this->argument('speed') ?? 0);

        Kafka::publishOn(KafkaConfig::KAFKAED_1_CONTROL)
            ->withMessage(new Message(body: $transmissionMsg))
            ->send();

        return 0;
    }
}
