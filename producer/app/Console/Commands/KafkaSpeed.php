<?php

namespace App\Console\Commands;

use App\Models\KafkaConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
        Cache::put(KafkaConfig::KAFKAED_1_CONFIG, $this->argument('speed') ?? 0);

        return 0;
    }
}
