<?php

namespace App\Console\Commands;

use Artalexm\KafkaedCommon\Messages\Transmission;
use Artalexm\KafkaedCommon\Models\KafkaConfig;
use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;

/**
 * Class KafkaControl.
 *
 * @package App\Console\Commands
 */
class KafkaControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kamsg:control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen for control commands';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        Kafka::createConsumer()
            ->subscribe(KafkaConfig::KAFKAED_1_CONTROL)
            ->withAutoCommit()
            ->withHandler([$this, 'onMessage'])
            ->build()
            ->consume();
    }

    /**
     * @param KafkaConsumerMessage $message
     */
    public function onMessage(KafkaConsumerMessage $message)
    {
        $body = optional((object)$message->getBody());

        if ($body->class === Transmission::class) {
            Cache::put(KafkaConfig::KAFKAED_1_CONFIG, $body->transmission ?? 0);
        }
    }
}
