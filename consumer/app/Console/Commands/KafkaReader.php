<?php

namespace App\Console\Commands;

use App\Events\Kafkaed1SpeedIsUpdated;
use Artalexm\KafkaedCommon\Models\KafkaConfig;
use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;

/**
 * Class KafkaReader.
 *
 * @package App\Console\Commands
 */
class KafkaReader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kamsg:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read messages from Kafka broker';

    /** @var float */
    protected float $start = 0.0;
    /** @var int */
    protected int $count = 0;

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $this->start = time();

        Kafka::createConsumer()
            ->subscribe(KafkaConfig::KAFKAED_1_MSGTOPIC)
            ->withAutoCommit()
            ->withHandler([$this, 'onMessage'])
            ->build()
            ->consume();

        return 0;
    }

    /**
     * @param KafkaConsumerMessage $message
     */
    public function onMessage(KafkaConsumerMessage $message)
    {
        $this->count++;
        $current = time();

        if ($current - $this->start < 2) {
            return;
        }

        $speed = round($this->count / ($current - $this->start));
        $this->count = 1;
        $this->start = $current;
        $transmission = optional((object)$message->getBody())->transmission ?? 0;

        Kafkaed1SpeedIsUpdated::broadcast($speed, $transmission);

        $this->output->write("<info>Speed: $speed msg/sec</info>\r");
    }
}
